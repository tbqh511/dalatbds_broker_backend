<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CrmCustomer;
use App\Models\CrmDeal;
use App\Models\CrmDealCommission;
use App\Models\CrmDealProduct;
use App\Models\CrmDealProductBooking;
use App\Models\CrmLead;
use App\Models\CrmLeadActivity;
use App\Models\Customer;
use App\Models\LocationsWard;
use App\Models\Property;
use App\Services\InAppNotificationService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleAdminController extends Controller
{
    public function index(Request $request)
    {
        $customer = Auth::guard('webapp')->user();

        // Sales team: all customers with role sale or sale_admin
        $salesTeam = Customer::query()
            ->where(fn ($q) => $q->where('role', 'sale')->orWhere('role', 'sale_admin'))
            ->get(['id', 'name', 'mobile', 'telegram_id', 'role']);

        // Recent unassigned leads (no sale_id)
        $unassignedLeads = CrmLead::with('customer')
            ->whereNull('sale_id')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Stats
        $stats = [
            'total_leads'      => CrmLead::count(),
            'unassigned_leads' => CrmLead::whereNull('sale_id')->count(),
            'total_sales'      => $salesTeam->count(),
            'converted_leads'  => CrmLead::where('status', 'converted')->count(),
        ];

        return view('frontend_dashboard_sale_admin', compact('customer', 'salesTeam', 'unassignedLeads', 'stats'));
    }

    public function getAssignData(Request $request)
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer || (!$customer->isSaleAdmin() && !$customer->hasRole('bds_admin'))) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            return $this->_getAssignDataInner($request, $customer);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('getAssignData error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    private function _getAssignDataInner(Request $request, $customer)
    {
        $districtCode = config('location.district_code');
        $categoryMap  = Category::where('status', '1')->pluck('category', 'id');
        $wardMap      = LocationsWard::where('district_code', $districtCode)->pluck('full_name', 'code');

        // ALL leads (all statuses, assigned or not) — latest 100
        $rawLeads = CrmLead::with(['customer', 'sale'])
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        // Sales team
        $salesTeam = Customer::query()
            ->where(fn ($q) => $q->where('role', 'sale')->orWhere('role', 'sale_admin'))
            ->get(['id', 'name', 'role', 'work_area']);

        // Active lead counts per sale (non-converted/lost)
        $saleIds = $salesTeam->pluck('id');
        $activeLeadCounts = CrmLead::whereIn('sale_id', $saleIds)
            ->whereNotIn('status', ['converted', 'lost', 'bad-contact'])
            ->selectRaw('sale_id, count(*) as cnt')
            ->groupBy('sale_id')
            ->pluck('cnt', 'sale_id');

        // Today's assignments (for history tab)
        $assignedToday = CrmLead::with(['customer', 'sale'])
            ->whereNotNull('sale_id')
            ->whereNotNull('assigned_at')
            ->where('assigned_at', '>=', Carbon::today())
            ->orderBy('assigned_at', 'desc')
            ->limit(30)
            ->get();

        $now = Carbon::now();

        // Build leads DTO (all leads)
        $leads = $rawLeads->map(function ($lead) use ($categoryMap, $wardMap, $now, $salesTeam, $activeLeadCounts) {
            $createdAt  = $lead->created_at ?? $now;
            $ageHours   = $createdAt->diffInHours($now);
            $ageMinutes = $createdAt->diffInMinutes($now);

            if ($ageHours < 3)      { $priority = 'hot'; }
            elseif ($ageHours < 24) { $priority = 'medium'; }
            elseif ($ageHours < 72) { $priority = 'normal'; }
            else                    { $priority = 'low'; }

            if ($ageMinutes < 60)       { $timeAgo = $ageMinutes . ' phút trước'; }
            elseif ($ageHours < 24)     { $timeAgo = $ageHours . ' giờ trước'; }
            elseif ($ageHours < 48)     { $timeAgo = 'Hôm qua'; }
            else                        { $timeAgo = (int) ($ageHours / 24) . ' ngày trước'; }

            $catNames  = collect($lead->categories ?? [])->map(fn ($id) => $categoryMap[$id] ?? null)->filter()->values()->all();
            $wardNames = collect($lead->wards ?? [])->map(fn ($c) => $wardMap[$c] ?? null)->filter()->values()->all();
            $wardCodes = $lead->wards ?? [];

            $statusRaw = $lead->getRawOriginal('status');
            $isAssigned = !is_null($lead->sale_id);
            $saleName   = $lead->sale?->name ?? '';

            // Sale suggestion: match work_area with lead wards (only for unassigned)
            $suggestion = null;
            if (!$isAssigned) {
                foreach ($salesTeam as $s) {
                    $workArea = $s->work_area ?? '';
                    foreach ($wardCodes as $code) {
                        if ($code && str_contains($workArea, (string) $code)) {
                            $suggestion = $s->name;
                            break 2;
                        }
                    }
                }
                if (!$suggestion) {
                    $lightestSale = $salesTeam->sortBy(fn ($s) => $activeLeadCounts[$s->id] ?? 0)->first();
                    $suggestion   = $lightestSale?->name;
                }
            }

            $budgetMax = (float) ($lead->demand_rate_max ?? 0);
            if ($budgetMax >= 3_000_000_000)     { $budgetTier = 'high'; }
            elseif ($budgetMax >= 1_000_000_000) { $budgetTier = 'medium'; }
            else                                  { $budgetTier = 'low'; }

            $customerName  = $lead->customer?->full_name ?? 'Khách vãng lai';
            $customerPhone = $lead->customer?->contact ?? '';
            $parts         = preg_split('/\s+/', trim($customerName));
            $initials      = count($parts) >= 2
                ? mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr(end($parts), 0, 1))
                : mb_strtoupper(mb_substr($customerName, 0, 2));

            return [
                'id'          => $lead->id,
                'name'        => $customerName,
                'phone'       => $customerPhone,
                'initials'    => $initials,
                'time_ago'    => $timeAgo,
                'priority'    => $priority,
                'source_note' => $lead->source_note ?? '',
                'lead_type'   => $lead->getRawOriginal('lead_type') === 'buy' ? 'Mua' : 'Thuê',
                'purpose'     => $lead->purpose ?? '',
                'categories'  => $catNames,
                'wards'       => $wardNames,
                'budget_min'  => format_vnd($lead->demand_rate_min ?? 0),
                'budget_max'  => format_vnd($lead->demand_rate_max ?? 0),
                'budget_tier' => $budgetTier,
                'suggestion'  => $suggestion,
                'status_raw'  => $statusRaw,
                'is_assigned' => $isAssigned,
                'sale_name'   => $saleName,
            ];
        })->values()->all();

        // Budget pool counts — only for unassigned leads
        $budgetCounts = ['high' => 0, 'medium' => 0, 'low' => 0];
        foreach ($leads as $l) {
            if (!$l['is_assigned']) {
                $budgetCounts[$l['budget_tier']]++;
            }
        }

        // Status counts
        $statusCounts = ['unassigned' => 0, 'new' => 0, 'contacted' => 0, 'converted' => 0, 'failed' => 0];
        foreach ($leads as $l) {
            if (!$l['is_assigned']) {
                $statusCounts['unassigned']++;
            }
            $s = $l['status_raw'];
            if ($s === 'new')                               { $statusCounts['new']++; }
            elseif ($s === 'contacted')                     { $statusCounts['contacted']++; }
            elseif ($s === 'converted')                     { $statusCounts['converted']++; }
            elseif ($s === 'bad-contact' || $s === 'lost')  { $statusCounts['failed']++; }
        }

        // Sales DTO
        $sales = $salesTeam->map(function ($s) use ($activeLeadCounts) {
            $count    = $activeLeadCounts[$s->id] ?? 0;
            $workload = $count >= 10 ? 'high' : ($count >= 5 ? 'mid' : 'low');
            $parts    = preg_split('/\s+/', trim($s->name));
            $initials = count($parts) >= 2
                ? mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr(end($parts), 0, 1))
                : mb_strtoupper(mb_substr($s->name, 0, 2));

            return [
                'id'           => $s->id,
                'name'         => $s->name,
                'initials'     => $initials,
                'role'         => $s->role,
                'active_leads' => $count,
                'workload'     => $workload,
            ];
        })->values()->all();

        // History DTO (today's assignments)
        $history = $assignedToday->map(function ($lead) {
            $customerName = $lead->customer?->full_name ?? 'Khách vãng lai';
            $saleName     = $lead->sale?->name ?? '';
            $parts        = preg_split('/\s+/', trim($saleName));
            $initials     = count($parts) >= 2
                ? mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr(end($parts), 0, 1))
                : mb_strtoupper(mb_substr($saleName, 0, 2));
            $typeLabel    = $lead->getRawOriginal('lead_type') === 'buy' ? 'Mua' : 'Thuê';

            return [
                'sale_name'     => $saleName,
                'sale_initials' => $initials,
                'customer_name' => $customerName,
                'lead_type'     => $typeLabel,
                'budget_label'  => format_vnd($lead->demand_rate_min ?? 0) . ' – ' . format_vnd($lead->demand_rate_max ?? 0),
                'time_label'    => $lead->assigned_at ? $lead->assigned_at->format('H:i') : '',
            ];
        })->values()->all();

        return response()->json([
            'success'       => true,
            'leads'         => $leads,
            'sales'         => $sales,
            'history'       => $history,
            'budget_counts' => $budgetCounts,
            'status_counts' => $statusCounts,
        ]);
    }

    public function getKpiTeamData(Request $request): \Illuminate\Http\JsonResponse
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer || !$customer->isSaleAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $now        = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $weekStart  = $now->copy()->startOfWeek();

        // Q1 — Sales team
        $salesTeam = Customer::where(fn ($q) => $q->where('role', 'sale')->orWhere('role', 'sale_admin'))
            ->get(['id', 'name', 'telegram_id', 'role']);
        $saleIds = $salesTeam->pluck('id')->all();

        if (empty($saleIds)) {
            return response()->json([
                'success'     => true,
                'team'        => $this->emptyTeamSummary($now),
                'leaderboard' => [],
                'sales'       => [],
            ]);
        }

        // Q2 — All leads for the team
        $allLeads    = CrmLead::whereIn('sale_id', $saleIds)
            ->get(['id', 'sale_id', 'status', 'assigned_at', 'updated_at', 'created_at']);
        $leadsBySale = $allLeads->groupBy('sale_id');
        $allLeadIds  = $allLeads->pluck('id')->all();
        $leadSaleMap = $allLeads->pluck('sale_id', 'id'); // lead_id → sale_id

        // Q3 — All deals for the team's leads
        $allDeals = empty($allLeadIds)
            ? collect()
            : CrmDeal::whereIn('lead_id', $allLeadIds)
                ->get(['id', 'lead_id', 'status', 'amount', 'updated_at', 'created_at']);
        $dealIds     = $allDeals->pluck('id')->all();
        $dealsBySale = $allDeals
            ->groupBy(fn ($d) => $leadSaleMap[$d->lead_id] ?? null)
            ->filter(fn ($v, $k) => $k !== null);

        // Q4 — First activity per lead (for avg response time)
        $firstActivities = empty($allLeadIds)
            ? collect()
            : CrmLeadActivity::whereIn('lead_id', $allLeadIds)
                ->whereIn('actor_id', $saleIds)
                ->selectRaw('lead_id, MIN(created_at) as first_at')
                ->groupBy('lead_id')
                ->pluck('first_at', 'lead_id');

        // Q5 — Commissions (NOTE: commission.sale_id → users.id, not customers.id)
        // Must join through deal → lead → sale_id
        $allCommissions = empty($dealIds)
            ? collect()
            : CrmDealCommission::whereIn('deal_id', $dealIds)
                ->where('status', '!=', 'cancelled')
                ->get(['deal_id', 'sale_commission', 'status', 'created_at']);
        $commSumByDealId = $allCommissions->groupBy('deal_id')
            ->map(fn ($g) => $g->sum(fn ($c) => (float) $c->getRawOriginal('sale_commission')));
        $commSumBySale = [];
        foreach ($dealsBySale as $saleId => $saleDeals) {
            $commSumBySale[$saleId] = $saleDeals->sum(fn ($d) => $commSumByDealId[$d->id] ?? 0);
        }

        // Q6 — Bookings this week
        $bookingCountBySale = [];
        if (!empty($allLeadIds)) {
            $weekBookings = CrmDealProductBooking::whereHas(
                'crmDealProduct.deal',
                fn ($q) => $q->whereIn('lead_id', $allLeadIds)
            )
                ->where('booking_date', '>=', $weekStart->toDateString())
                ->with('crmDealProduct:id,deal_id')
                ->get(['id', 'crm_deals_products_id', 'booking_date']);

            foreach ($weekBookings as $b) {
                $dealId = $b->crmDealProduct->deal_id ?? null;
                $leadId = $allDeals->firstWhere('id', $dealId)?->lead_id ?? null;
                $sId    = $leadSaleMap[$leadId] ?? null;
                if ($sId) {
                    $bookingCountBySale[$sId] = ($bookingCountBySale[$sId] ?? 0) + 1;
                }
            }
        }

        // Q7 — Stuck deal products (not updated > 5 days, not in final state)
        $stuckBySale = [];
        if (!empty($dealIds)) {
            $stuckProducts = CrmDealProduct::whereIn('deal_id', $dealIds)
                ->where('updated_at', '<', $now->copy()->subDays(5))
                ->whereNotIn('status', ['viewed_failed'])
                ->whereHas('deal', fn ($q) => $q->whereNotIn('status', ['closed']))
                ->with('deal:id,lead_id')
                ->get(['id', 'deal_id', 'status', 'updated_at']);

            foreach ($stuckProducts as $sp) {
                $sId = $leadSaleMap[$sp->deal->lead_id ?? null] ?? null;
                if ($sId) {
                    $stuckBySale[$sId][] = [
                        'deal_id'    => $sp->deal_id,
                        'days_stuck' => (int) $sp->updated_at->diffInDays($now),
                    ];
                }
            }
        }

        // Q8 — Recent activities (last seen + detail panel)
        $recentActivities = CrmLeadActivity::whereIn('actor_id', $saleIds)
            ->with(['lead.customer:id,full_name'])
            ->orderBy('created_at', 'desc')
            ->limit(\count($saleIds) * 5)
            ->get(['id', 'lead_id', 'actor_id', 'type', 'content', 'created_at']);
        $activitiesBySale = $recentActivities->groupBy('actor_id');
        $lastSeenBySale   = $activitiesBySale->map(fn ($acts) => $acts->first()->created_at);

        // Team summary (using already-loaded data, plus 1 extra revenue query)
        $dealsThisMonth  = $allDeals->filter(fn ($d) => $d->created_at >= $monthStart)->count();
        $closedThisMonth = $allLeads->filter(
            fn ($l) => $l->getRawOriginal('status') === 'converted' && $l->updated_at >= $monthStart
        )->count();
        $revenueThisMonth = empty($allLeadIds) ? 0 :
            CrmDeal::whereIn('lead_id', $allLeadIds)
                ->where('status', 'closed')
                ->where('updated_at', '>=', $monthStart)
                ->sum('amount');
        $commThisMonth = $allCommissions->filter(fn ($c) => $c->created_at >= $monthStart)
            ->sum(fn ($c) => (float) $c->getRawOriginal('sale_commission'));

        $teamSummary = [
            'month_label'           => $now->month . ' / ' . $now->year,
            'team_count'            => $salesTeam->count(),
            'deals_this_month'      => $dealsThisMonth,
            'closed_this_month'     => $closedThisMonth,
            'revenue_this_month'    => format_vnd((float) $revenueThisMonth),
            'commission_this_month' => format_vnd($commThisMonth),
        ];

        // Per-sale KPIs (pure PHP, no extra queries)
        $salesData = $salesTeam->map(function ($sale) use (
            $leadsBySale, $commSumBySale, $firstActivities, $leadSaleMap,
            $monthStart, $stuckBySale, $bookingCountBySale, $activitiesBySale,
            $lastSeenBySale, $now
        ) {
            $saleLeads = $leadsBySale[$sale->id] ?? collect();

            $leadMoi  = $saleLeads->filter(fn ($l) => $l->getRawOriginal('status') === 'new' && $l->assigned_at && $l->assigned_at >= $monthStart)->count();
            $dangCham = $saleLeads->filter(fn ($l) => in_array($l->getRawOriginal('status'), ['new', 'contacted']))->count();
            $daChot   = $saleLeads->filter(fn ($l) => $l->getRawOriginal('status') === 'converted' && $l->updated_at >= $monthStart)->count();
            $hhDuKien = $commSumBySale[$sale->id] ?? 0;

            $totalAssigned  = $saleLeads->count();
            $totalConverted = $saleLeads->filter(fn ($l) => $l->getRawOriginal('status') === 'converted')->count();
            $closeRate      = $totalAssigned > 0 ? round($totalConverted / $totalAssigned * 100, 1) : 0;

            // Avg response time
            $responseHours = [];
            foreach ($saleLeads as $lead) {
                if (!$lead->assigned_at) {
                    continue;
                }
                $firstAt = $firstActivities[$lead->id] ?? null;
                if ($firstAt) {
                    $responseHours[] = Carbon::parse($lead->assigned_at)->diffInMinutes(Carbon::parse($firstAt)) / 60;
                }
            }
            $avgResponseH = \count($responseHours) > 0
                ? round(array_sum($responseHours) / \count($responseHours), 1)
                : null;

            $bookingsWeek = $bookingCountBySale[$sale->id] ?? 0;
            $stuckItems   = $stuckBySale[$sale->id] ?? [];
            $needsSupport = \count($stuckItems) > 0 || ($avgResponseH !== null && $avgResponseH > 5);

            $lastSeenAt    = $lastSeenBySale[$sale->id] ?? null;
            $isOnline      = $lastSeenAt && Carbon::parse($lastSeenAt)->diffInMinutes($now) < 30;
            $lastSeenLabel = $lastSeenAt ? $this->formatLastSeen(Carbon::parse($lastSeenAt), $now) : null;

            $parts    = preg_split('/\s+/', trim($sale->name));
            $initials = \count($parts) >= 2
                ? mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr(end($parts), 0, 1))
                : mb_strtoupper(mb_substr($sale->name, 0, 2));

            $saleActivities = ($activitiesBySale[$sale->id] ?? collect())->take(5);
            $recentActData  = $saleActivities->map(fn ($act) => [
                'type_label'    => $act->getTypeLabel(),
                'content'       => $act->content,
                'customer_name' => $act->lead?->customer?->full_name ?? 'Khách',
                'time_ago'      => $this->formatLastSeen($act->created_at, $now),
            ])->values()->all();

            return [
                'id'               => $sale->id,
                'name'             => $sale->name,
                'initials'         => $initials,
                'role'             => $sale->role,
                'telegram_id'      => $sale->telegram_id,
                'is_online'        => $isOnline,
                'last_seen_label'  => $isOnline ? 'Online' : ($lastSeenLabel ?? 'Chưa có hoạt động'),
                'lead_moi'         => $leadMoi,
                'dang_cham'        => $dangCham,
                'da_chot'          => $daChot,
                'hh_du_kien'       => format_vnd($hhDuKien),
                'close_rate'       => $closeRate,
                'avg_response_h'   => $avgResponseH,
                'bookings_week'    => $bookingsWeek,
                'needs_support'    => $needsSupport,
                'stuck_items'      => $stuckItems,
                'recent_activities'=> $recentActData,
            ];
        })->values();

        // Leaderboard: sort by da_chot desc
        $ranked    = $salesData->sortByDesc('da_chot')->values();
        $maxChot   = max(1, $ranked->max('da_chot'));
        $leaderboard = $ranked->map(fn ($s, $i) => [
            'rank'    => $i + 1,
            'id'      => $s['id'],
            'name'    => $s['name'],
            'da_chot' => $s['da_chot'],
            'bar_pct' => $s['da_chot'] > 0 ? (int) round($s['da_chot'] / $maxChot * 100) : 5,
        ])->values()->all();

        // Attach rank to each sale
        $rankMap   = collect($leaderboard)->pluck('rank', 'id');
        $salesFull = $salesData->map(fn ($s) => array_merge($s, ['rank' => $rankMap[$s['id']] ?? 99]))->values();

        return response()->json([
            'success'     => true,
            'team'        => $teamSummary,
            'leaderboard' => $leaderboard,
            'sales'       => $salesFull,
        ]);
    }

    public function sendSupportReminder(Request $request): \Illuminate\Http\JsonResponse
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer || !$customer->isSaleAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $saleId = (int) $request->input('sale_id');
        $sale   = Customer::find($saleId);

        if (!$sale || !in_array($sale->role, ['sale', 'sale_admin'])) {
            return response()->json(['success' => false, 'message' => 'Sale không tồn tại'], 404);
        }

        if (!$sale->telegram_id) {
            return response()->json(['success' => false, 'message' => 'Sale chưa có Telegram ID'], 400);
        }

        $msg  = "📋 *Nhắc nhở từ Sale Admin*\n\n"
              . "Bạn có deal/lead cần cập nhật trạng thái.\n"
              . "Vui lòng kiểm tra và cập nhật ngay hôm nay.\n\n"
              . "_— " . $customer->name . "_";

        $notif = app(NotificationService::class);
        $sent  = $notif->sendToCustomer($sale, $msg);

        // In-app notification
        app(InAppNotificationService::class)->notify($sale, 'deal_stuck', 'deal', 'stuck', [
            'title' => 'Nhắc nhở từ Sale Admin',
            'body'  => 'Bạn có deal/lead cần cập nhật trạng thái. Vui lòng kiểm tra.',
            'actor_id' => $customer->id,
        ]);

        return response()->json([
            'success' => $sent,
            'message' => $sent ? 'Đã gửi nhắc nhở' : 'Không gửi được (kiểm tra bot token)',
        ]);
    }

    private function emptyTeamSummary(Carbon $now): array
    {
        return [
            'month_label'           => $now->month . ' / ' . $now->year,
            'team_count'            => 0,
            'deals_this_month'      => 0,
            'closed_this_month'     => 0,
            'revenue_this_month'    => '0đ',
            'commission_this_month' => '0đ',
        ];
    }

    public function getAdminReportsData(Request $request): \Illuminate\Http\JsonResponse
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer || $customer->getEffectiveRole() !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $period = $request->get('period', 'month');
        $now    = Carbon::now();

        switch ($period) {
            case 'q1':
                $start = Carbon::create($now->year, 1, 1)->startOfDay();
                break;
            case '6months':
                $start = $now->copy()->subMonths(6)->startOfMonth();
                break;
            case 'year':
                $start = Carbon::create($now->year, 1, 1)->startOfDay();
                break;
            default: // month
                $start = $now->copy()->startOfMonth();
                break;
        }
        $end = $now;

        // Previous period for delta comparison
        $duration  = max($start->diffInSeconds($end), 1);
        $prevEnd   = $start->copy()->subSecond();
        $prevStart = $prevEnd->copy()->subSeconds($duration);

        // ── Revenue (doanh số GD) ──
        $revenue     = (float) CrmDeal::where('status', 'closed')->whereBetween('updated_at', [$start, $end])->sum('amount');
        $prevRevenue = (float) CrmDeal::where('status', 'closed')->whereBetween('updated_at', [$prevStart, $prevEnd])->sum('amount');

        // ── Deals chốt ──
        $dealsCount     = CrmDeal::where('status', 'closed')->whereBetween('updated_at', [$start, $end])->count();
        $prevDealsCount = CrmDeal::where('status', 'closed')->whereBetween('updated_at', [$prevStart, $prevEnd])->count();

        // ── Hoa hồng phát sinh ──
        $commission = (float) DB::table('crm_deals_commissions')
            ->join('crm_deals', 'crm_deals_commissions.deal_id', '=', 'crm_deals.id')
            ->where('crm_deals.status', 'closed')
            ->whereBetween('crm_deals.updated_at', [$start, $end])
            ->sum(DB::raw('crm_deals_commissions.sale_commission + crm_deals_commissions.app_commission + crm_deals_commissions.lead_commission + crm_deals_commissions.owner_commission'));
        $prevCommission = (float) DB::table('crm_deals_commissions')
            ->join('crm_deals', 'crm_deals_commissions.deal_id', '=', 'crm_deals.id')
            ->where('crm_deals.status', 'closed')
            ->whereBetween('crm_deals.updated_at', [$prevStart, $prevEnd])
            ->sum(DB::raw('crm_deals_commissions.sale_commission + crm_deals_commissions.app_commission + crm_deals_commissions.lead_commission + crm_deals_commissions.owner_commission'));

        // ── Khách hàng mới ──
        $newCustomers     = CrmCustomer::whereBetween('created_at', [$start, $end])->count();
        $prevNewCustomers = CrmCustomer::whereBetween('created_at', [$prevStart, $prevEnd])->count();

        // ── BĐS live (snapshot) ──
        $liveBds    = Property::where('status', 1)->count();
        $totalViews = (int) Property::sum('total_click');

        // ── Revenue bar chart — 6 tháng gần nhất ──
        $barData = [];
        for ($i = 5; $i >= 0; $i--) {
            $m      = $now->copy()->subMonths($i);
            $mStart = $m->copy()->startOfMonth();
            $mEnd   = $m->copy()->endOfMonth();
            $mRev   = (float) CrmDeal::where('status', 'closed')->whereBetween('updated_at', [$mStart, $mEnd])->sum('amount');
            $barData[] = [
                'label'      => 'T' . $m->month,
                'value'      => round($mRev / 1_000_000_000, 1),
                'is_current' => $i === 0,
            ];
        }

        // ── Deal conversion funnel ──
        $leadsTotal  = CrmLead::whereBetween('created_at', [$start, $end])->count();
        $dealsTotal  = CrmDeal::whereBetween('created_at', [$start, $end])->count();
        $closedTotal = CrmDeal::where('status', 'closed')->whereBetween('updated_at', [$start, $end])->count();
        $lostTotal   = CrmLead::where('status', 'lost')->whereBetween('updated_at', [$start, $end])->count();
        $convRate    = $leadsTotal > 0 ? (int) round($closedTotal / $leadsTotal * 100) : 0;

        // ── Top Brokers ──
        $topBrokersRaw = DB::table('crm_deals')
            ->join('crm_leads', 'crm_deals.lead_id', '=', 'crm_leads.id')
            ->join('customers', 'crm_leads.user_id', '=', 'customers.id')
            ->where('crm_deals.status', 'closed')
            ->whereBetween('crm_deals.updated_at', [$start, $end])
            ->selectRaw('customers.id, customers.name, COUNT(crm_deals.id) as deals_count, SUM(crm_deals.amount) as total_revenue')
            ->groupBy('customers.id', 'customers.name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        $topBrokers = $topBrokersRaw->values()->map(function ($b, $i) {
            return [
                'rank'          => $i + 1,
                'name'          => $b->name,
                'deals'         => (int) $b->deals_count,
                'revenue_raw'   => (float) $b->total_revenue,
                'revenue_label' => $this->formatRevenue((float) $b->total_revenue),
            ];
        });

        // ── Loại BĐS giao dịch nhiều nhất ──
        $propTypesRaw = DB::table('crm_deals_products')
            ->join('crm_deals', 'crm_deals_products.deal_id', '=', 'crm_deals.id')
            ->join('propertys', 'crm_deals_products.property_id', '=', 'propertys.id')
            ->join('categories', 'propertys.category_id', '=', 'categories.id')
            ->where('crm_deals.status', 'closed')
            ->whereBetween('crm_deals.updated_at', [$start, $end])
            ->selectRaw('categories.id, categories.category as name, COUNT(crm_deals_products.id) as cnt')
            ->groupBy('categories.id', 'categories.category')
            ->orderByDesc('cnt')
            ->limit(5)
            ->get();

        $ptTotal    = $propTypesRaw->sum('cnt');
        $propTypes  = $propTypesRaw->map(fn ($t) => [
            'name' => $t->name,
            'cnt'  => (int) $t->cnt,
            'pct'  => $ptTotal > 0 ? (int) round($t->cnt / $ptTotal * 100) : 0,
        ]);

        // ── Period label ──
        $periodLabels = [
            'month'   => 'TỔNG QUAN THÁNG ' . $now->month . ' / ' . $now->year,
            'q1'      => 'TỔNG QUAN QUÝ 1 / ' . $now->year,
            '6months' => 'TỔNG QUAN 6 THÁNG GẦN NHẤT',
            'year'    => 'TỔNG QUAN NĂM ' . $now->year,
        ];

        return response()->json([
            'hero' => [
                'label'            => $periodLabels[$period] ?? $periodLabels['month'],
                'revenue_label'    => $this->formatRevenue($revenue),
                'deals_closed'     => $dealsCount,
                'commission_label' => $this->formatRevenue($commission),
                'live_bds'         => $liveBds,
                'total_views'      => $totalViews,
            ],
            'metrics' => [
                'revenue' => [
                    'label'     => $this->formatRevenue($revenue),
                    'delta'     => $this->calcDelta($revenue, $prevRevenue),
                    'delta_dir' => $revenue >= $prevRevenue ? 'up' : 'dn',
                    'delta_abs' => false,
                ],
                'deals' => [
                    'label'     => (string) $dealsCount,
                    'delta'     => $dealsCount - $prevDealsCount,
                    'delta_dir' => $dealsCount >= $prevDealsCount ? 'up' : 'dn',
                    'delta_abs' => true,
                ],
                'commission' => [
                    'label'     => $this->formatRevenue($commission),
                    'delta'     => $this->calcDelta($commission, $prevCommission),
                    'delta_dir' => $commission >= $prevCommission ? 'up' : 'dn',
                    'delta_abs' => false,
                ],
                'new_customers' => [
                    'label'     => (string) $newCustomers,
                    'delta'     => $newCustomers - $prevNewCustomers,
                    'delta_dir' => $newCustomers >= $prevNewCustomers ? 'up' : 'dn',
                    'delta_abs' => true,
                ],
            ],
            'bar_chart'      => $barData,
            'funnel'         => [
                'leads'         => $leadsTotal,
                'deals_created' => $dealsTotal,
                'closed'        => $closedTotal,
                'lost'          => $lostTotal,
                'conv_rate'     => $convRate,
            ],
            'top_brokers'    => $topBrokers,
            'property_types' => $propTypes,
        ]);
    }

    private function formatRevenue(float $amount): string
    {
        if ($amount >= 1_000_000_000) {
            return round($amount / 1_000_000_000, 1) . ' tỷ';
        }
        if ($amount >= 1_000_000) {
            return round($amount / 1_000_000) . ' tr';
        }

        return number_format($amount) . ' đ';
    }

    private function calcDelta(float $current, float $prev): ?float
    {
        if ($prev == 0) {
            return null;
        }

        return round(($current - $prev) / $prev * 100, 1);
    }

    private function formatLastSeen(Carbon $ts, Carbon $now): string
    {
        $min = (int) $ts->diffInMinutes($now);
        if ($min < 60) {
            return $min . ' phút trước';
        }
        $h = (int) $ts->diffInHours($now);
        if ($h < 24) {
            return $h . 'h trước';
        }
        if ($h < 48) {
            return 'Hôm qua';
        }

        return ((int) ($h / 24)) . ' ngày trước';
    }
}
