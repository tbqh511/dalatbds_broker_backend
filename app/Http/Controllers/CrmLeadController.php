<?php

namespace App\Http\Controllers;

use App\Http\Requests\Crm\Lead\StoreLeadRequest;
use App\Http\Requests\Crm\Lead\UpdateLeadRequest;
use App\Models\CrmDeal;
use App\Models\CrmLeadActivity;
use App\Models\Customer;
use App\Services\CrmLeadService;
use App\Services\InAppNotificationService;
use App\Services\NotificationService;
use App\Services\Telegram\TelegramMessageTemplates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use App\Models\CrmLead;
use App\Models\Category;
use App\Models\LocationsWard;

class CrmLeadController extends Controller
{
    protected $leadService;
    protected $notificationService;
    protected $inAppNotifService;

    public function __construct(CrmLeadService $leadService, NotificationService $notificationService, InAppNotificationService $inAppNotifService)
    {
        $this->leadService = $leadService;
        $this->notificationService = $notificationService;
        $this->inAppNotifService = $inAppNotifService;
    }

    public function index(Request $request)
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer) {
            return redirect()->route('webapp');
        }

        $filters = [
            'search'    => $request->input('search'),
            'status'    => $request->input('status'),
            'lead_type' => $request->input('lead_type'),
        ];

        $leads = $this->leadService->getLeads($customer->id, 10, $filters);

        $categoryMap  = Category::where('status', '1')->pluck('category', 'id');
        $districtCode = config('location.district_code');
        $wardMap      = LocationsWard::where('district_code', $districtCode)->pluck('full_name', 'code');

        if ($request->ajax()) {
            $statusLabels = [
                'New'       => 'Mới',
                'Contacted' => 'Đã liên hệ',
                'Converted' => 'Chuyển đổi',
                'Lost'      => 'Thất bại',
            ];
            $data = $leads->map(function ($lead) use ($categoryMap, $wardMap, $statusLabels) {
                $catNames  = collect($lead->categories ?? [])
                    ->map(fn($id) => $categoryMap[$id] ?? null)->filter()->implode(', ');
                $wardNames = collect($lead->wards ?? [])
                    ->map(fn($c) => $wardMap[$c] ?? null)->filter()->implode(', ');
                $street = '';
                if ($lead->note && str_contains($lead->note, 'Tên đường:')) {
                    $street = trim(substr($lead->note, strpos($lead->note, 'Tên đường:') + strlen('Tên đường:')));
                }
                $rawStatus = strtolower($lead->getRawOriginal('status'));
                return [
                    'id'               => $lead->id,
                    'customer_name'    => $lead->customer?->full_name ?? 'Khách vãng lai',
                    'customer_contact' => $lead->customer?->contact ?? '',
                    'status_label'     => $statusLabels[$lead->status] ?? $lead->status,
                    'status_raw'       => $rawStatus,
                    'lead_type'        => $lead->lead_type === 'Buy' ? 'Mua' : 'Thuê',
                    'budget'           => $lead->budget_label ?: (
                        ((float)($lead->demand_rate_min ?? 0) > 0 || (float)($lead->demand_rate_max ?? 0) > 0)
                            ? (((float)($lead->demand_rate_min ?? 0) > 0 ? format_vnd($lead->demand_rate_min) : '?') . ' – ' . ((float)($lead->demand_rate_max ?? 0) > 0 ? format_vnd($lead->demand_rate_max) : '?'))
                            : 'Thỏa thuận'
                    ),
                    'categories'       => $catNames,
                    'wards'            => $wardNames,
                    'street'           => $street,
                    'date'             => $lead->created_at->format('d/m/Y'),
                    'show_url'         => route('webapp.leads.show', $lead->id),
                    'edit_url'         => route('webapp.leads.edit', $lead->id),
                    'delete_url'       => route('webapp.leads.destroy', $lead->id),
                ];
            });
            return response()->json([
                'leads'     => $data,
                'has_more'  => $leads->hasMorePages(),
                'next_page' => $leads->currentPage() + 1,
                'total'     => $leads->total(),
            ]);
        }

        return view('frontend_dashboard_leads', compact('leads', 'customer', 'categoryMap', 'wardMap'));
    }

    public function create()
    {
        $customer = Auth::guard('webapp')->user();
        return view('frontend_dashboard_lead_create', compact('customer'));
    }

    public function store(StoreLeadRequest $request)
    {
        $customer = Auth::guard('webapp')->user();
        
        try {
            $this->leadService->createLead($request->validated(), $customer->id);
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'redirect_url' => route('webapp.leads')]);
            }
            return redirect()->route('webapp.leads')->with('success', 'Lead created successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $customer = Auth::guard('webapp')->user();
        $lead = $this->leadService->getLead($id);

        $canView = $lead && (
            $lead->user_id == $customer->id ||
            $lead->sale_id == $customer->id ||
            $customer->isSaleAdmin()
        );

        if (!$canView) {
            return redirect()->route('webapp.leads')->with('error', 'Lead not found or unauthorized');
        }

        $lead->load(['customer', 'sale', 'deal.products.property', 'deal.products.bookings', 'activities.actor']);

        $categoryMap  = Category::where('status', '1')->pluck('category', 'id');
        $districtCode = config('location.district_code');
        $wardMap      = LocationsWard::where('district_code', $districtCode)->pluck('full_name', 'code');

        $salesList = $customer->isSaleAdmin()
            ? Customer::query()
                ->where(fn ($q) => $q->where('role', 'sale')->orWhere('role', 'sale_admin'))
                ->get(['id', 'name'])
            : collect();

        return view('frontend_dashboard_lead_show', compact('lead', 'customer', 'categoryMap', 'wardMap', 'salesList'));
    }

    public function updateStatus(Request $request, $id)
    {
        $customer = Auth::guard('webapp')->user();
        $lead = $this->leadService->getLead($id);

        $canEdit = $lead && (
            $lead->user_id == $customer->id ||
            $lead->sale_id == $customer->id ||
            $customer->isSaleAdmin()
        );

        if (!$canEdit) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $newStatus  = $request->input('status');
        $actionType = $request->input('action_type', 'status_change');
        $note       = trim($request->input('note', ''));
        $oldStatus  = $lead->getRawOriginal('status');
        $lead->status = $newStatus;
        $lead->save();

        CrmLeadActivity::create([
            'lead_id'  => $lead->id,
            'actor_id' => $customer->id,
            'type'     => 'status_change',
            'content'  => "Đổi trạng thái: {$oldStatus} → {$newStatus}",
        ]);

        if ($note !== '') {
            CrmLeadActivity::create([
                'lead_id'  => $lead->id,
                'actor_id' => $customer->id,
                'type'     => $actionType,
                'content'  => $note,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function assignSale(Request $request, $id)
    {
        $customer = Auth::guard('webapp')->user();
        $lead = $this->leadService->getLead($id);

        if (!$lead || (!$customer->isSaleAdmin() && !$customer->hasRole('bds_admin'))) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $saleId = (int) $request->input('sale_id');
        $sale = Customer::query()
            ->where('id', $saleId)
            ->where(fn ($q) => $q->where('role', 'sale')->orWhere('role', 'sale_admin'))
            ->first();
        if (!$sale) {
            return response()->json(['success' => false, 'message' => 'Sale không hợp lệ'], 422);
        }

        $lead->sale_id     = $sale->id;
        $lead->assigned_at = Carbon::now();
        $lead->save();

        CrmLeadActivity::create([
            'lead_id'  => $lead->id,
            'actor_id' => $customer->id,
            'type'     => 'assignment',
            'content'  => "Phân công cho: {$sale->name}",
        ]);

        // Notify assigned sale via Telegram (requires bot started + notification settings)
        if ($sale->telegram_id && $sale->telegram_bot_started && $this->notificationService->shouldNotify($sale, 'lead', 'assigned', 'telegram')) {
            $lead->load('customer');
            $tpl = TelegramMessageTemplates::leadAssigned($lead);
            $this->notificationService->sendWithInlineKeyboard($sale->telegram_id, $tpl['text'], $tpl['keyboard']);
        }

        // In-app notification
        $lead->load('customer');
        $this->inAppNotifService->notify($sale, 'lead_assigned', 'lead', 'assigned', [
            'title' => 'Lead mới được assign cho bạn',
            'body'  => ($lead->customer->full_name ?? 'N/A') . ' — ' . ($lead->lead_type === 'buy' ? 'Mua' : 'Thuê'),
            'notifiable_type' => CrmLead::class,
            'notifiable_id'   => $lead->id,
            'actor_id'        => $customer->id,
            'data'  => [
                'lead_id'       => $lead->id,
                'customer_name' => $lead->customer->full_name ?? '',
                'customer_phone' => $lead->customer->contact ?? '',
                'lead_type'     => $lead->lead_type,
            ],
        ]);

        return response()->json(['success' => true, 'sale_name' => $sale->name]);
    }

    public function bulkAssign(Request $request)
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer || (!$customer->isSaleAdmin() && !$customer->hasRole('bds_admin'))) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $leadIds = $request->input('lead_ids');
        $saleId  = (int) $request->input('sale_id');

        if (!is_array($leadIds) || count($leadIds) === 0) {
            return response()->json(['success' => false, 'message' => 'Không có lead nào được chọn'], 422);
        }
        if (count($leadIds) > 50) {
            return response()->json(['success' => false, 'message' => 'Tối đa 50 lead mỗi lần'], 422);
        }

        $sale = Customer::query()
            ->where('id', $saleId)
            ->where(fn ($q) => $q->where('role', 'sale')->orWhere('role', 'sale_admin'))
            ->first();

        if (!$sale) {
            return response()->json(['success' => false, 'message' => 'Sale không hợp lệ'], 422);
        }

        $now      = Carbon::now();
        $assigned = [];
        $skipped  = [];

        DB::transaction(function () use ($leadIds, $sale, $customer, $now, &$assigned, &$skipped) {
            foreach ($leadIds as $leadId) {
                $lead = CrmLead::find((int) $leadId);
                if (!$lead || $lead->sale_id) {
                    $skipped[] = (int) $leadId;
                    continue;
                }

                $lead->sale_id     = $sale->id;
                $lead->assigned_at = $now;
                $lead->save();

                CrmLeadActivity::create([
                    'lead_id'  => $lead->id,
                    'actor_id' => $customer->id,
                    'type'     => 'assignment',
                    'content'  => "Phân công cho: {$sale->name}",
                ]);

                $assigned[] = $lead->id;
            }
        });

        // Send one Telegram notification for the whole batch
        if (count($assigned) > 0 && $sale->telegram_id && $sale->telegram_bot_started &&
            $this->notificationService->shouldNotify($sale, 'lead', 'assigned', 'telegram')) {
            $firstLead = CrmLead::find($assigned[0]);
            if ($firstLead) {
                if (count($assigned) === 1) {
                    $tpl = TelegramMessageTemplates::leadAssigned($firstLead);
                    $this->notificationService->sendWithInlineKeyboard($sale->telegram_id, $tpl['text'], $tpl['keyboard']);
                } else {
                    $this->notificationService->sendToCustomer($sale, 'Bạn được phân công ' . count($assigned) . ' lead mới. Vui lòng kiểm tra danh sách lead.');
                }
            }
        }

        // In-app notifications for bulk assign
        if (count($assigned) > 0) {
            if (count($assigned) === 1) {
                $firstLead = CrmLead::with('customer')->find($assigned[0]);
                if ($firstLead) {
                    $this->inAppNotifService->notify($sale, 'lead_assigned', 'lead', 'assigned', [
                        'title' => 'Lead mới được assign cho bạn',
                        'body'  => ($firstLead->customer->full_name ?? 'N/A') . ' — ' . ($firstLead->lead_type === 'buy' ? 'Mua' : 'Thuê'),
                        'notifiable_type' => CrmLead::class,
                        'notifiable_id'   => $firstLead->id,
                        'actor_id'        => $customer->id,
                        'data'  => ['lead_id' => $firstLead->id, 'customer_name' => $firstLead->customer->full_name ?? ''],
                    ]);
                }
            } else {
                $this->inAppNotifService->notify($sale, 'lead_assigned', 'lead', 'assigned', [
                    'title' => 'Bạn được phân công ' . count($assigned) . ' lead mới',
                    'body'  => 'Vui lòng kiểm tra danh sách lead để xử lý.',
                    'actor_id' => $customer->id,
                    'data'  => ['lead_ids' => $assigned, 'count' => count($assigned)],
                ]);
            }
        }

        return response()->json([
            'success'        => true,
            'assigned_count' => count($assigned),
            'assigned_ids'   => $assigned,
            'skipped_ids'    => $skipped,
            'sale_name'      => $sale->name,
        ]);
    }

    public function createDeal(Request $request, $id)
    {
        $customer = Auth::guard('webapp')->user();
        $lead = $this->leadService->getLead($id);

        $canCreate = $lead && (
            $lead->sale_id == $customer->id ||
            $customer->isSaleAdmin()
        );

        if (!$canCreate) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($lead->deal) {
            return response()->json(['success' => false, 'message' => 'Deal đã tồn tại'], 422);
        }

        $deal = CrmDeal::create([
            'lead_id'     => $lead->id,
            'customer_id' => $lead->customer_id,
            'status'      => 'open',
            'amount'      => 0,
        ]);

        $lead->status = 'converted';
        $lead->save();

        CrmLeadActivity::create([
            'lead_id'  => $lead->id,
            'actor_id' => $customer->id,
            'type'     => 'status_change',
            'content'  => 'Tạo Deal từ Lead, trạng thái chuyển sang Chuyển đổi',
        ]);

        return response()->json(['success' => true, 'deal_id' => $deal->id]);
    }

    public function edit($id)
    {
        $customer = Auth::guard('webapp')->user();
        $lead = $this->leadService->getLead($id);

        if (!$lead || $lead->user_id != $customer->id) {
            return redirect()->route('webapp.leads')->with('error', 'Lead not found or unauthorized');
        }

        return view('frontend_dashboard_lead_edit', compact('lead', 'customer'));
    }

    public function update(UpdateLeadRequest $request, $id)
    {
        $customer = Auth::guard('webapp')->user();
        
        $lead = $this->leadService->getLead($id);
        if (!$lead || $lead->user_id != $customer->id) {
            return back()->with('error', 'Unauthorized');
        }

        try {
            $updateData = [
                'lead_type' => $request->lead_type,
                'status' => $request->status,
                'source_note' => $request->note,
                'demand_rate_min' => $request->price_min,
                'demand_rate_max' => $request->price_max,
                'budget_label'    => $request->budget_label ?? '',
                'customer' => [
                    'full_name' => $request->name,
                    'contact' => $request->phone,
                ]
            ];

            $this->leadService->updateLead($id, $updateData);
            
            return redirect()->route('webapp.leads')->with('success', 'Lead updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $customer = Auth::guard('webapp')->user();
        $lead = $this->leadService->getLead($id);
        
        if (!$lead || $lead->user_id != $customer->id) {
             if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            return back()->with('error', 'Unauthorized');
        }

        $this->leadService->deleteLead($id);
        
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Lead deleted']);
        }
        return redirect()->route('webapp.leads')->with('success', 'Lead deleted');
    }

    /**
     * Show the assign-lead page (opened via Telegram web_app button, signed URL auth).
     * GET /webapp/leads/{id}/assign?signature=...
     */
    public function assignPage(Request $request, $id)
    {
        $lead = CrmLead::with(['customer', 'sale'])->find($id);

        if (!$lead) {
            abort(404, 'Lead không tồn tại');
        }

        $salesList = Customer::query()
            ->where(fn ($q) => $q->where('role', 'sale')->orWhere('role', 'sale_admin'))
            ->get(['id', 'name', 'mobile', 'role']);

        $postUrl = URL::temporarySignedRoute(
            'webapp.leads.do-assign',
            Carbon::now()->addHours(24),
            ['id' => $id]
        );

        $districtCode  = config('location.district_code');
        $categoryMap   = Category::where('status', '1')->pluck('category', 'id');
        $wardMap       = LocationsWard::where('district_code', $districtCode)->pluck('full_name', 'code');
        $categoryNames = collect($lead->categories ?? [])->map(fn ($id) => $categoryMap[$id] ?? null)->filter()->values()->all();
        $wardNames     = collect($lead->wards ?? [])->map(fn ($c) => $wardMap[$c] ?? null)->filter()->values()->all();

        return view('frontend_dashboard_assign_lead', compact('lead', 'salesList', 'postUrl', 'categoryNames', 'wardNames'));
    }

    /**
     * Process the lead assignment (signed URL auth, no session required).
     * POST /webapp/leads/{id}/assign?signature=...
     */
    public function doAssign(Request $request, $id)
    {
        $lead = CrmLead::with(['customer'])->find($id);

        if (!$lead) {
            return response()->json(['success' => false, 'message' => 'Lead không tồn tại'], 404);
        }

        if ($lead->sale_id) {
            $existingSale = Customer::find($lead->sale_id);
            return response()->json([
                'success'          => false,
                'already_assigned' => true,
                'sale_name'        => $existingSale?->name ?? 'N/A',
            ], 409);
        }

        $saleId = (int) $request->input('sale_id');
        $sale = Customer::query()
            ->where('id', $saleId)
            ->where(fn ($q) => $q->where('role', 'sale')->orWhere('role', 'sale_admin'))
            ->first();

        if (!$sale) {
            return response()->json(['success' => false, 'message' => 'Sale không hợp lệ'], 422);
        }

        $lead->sale_id     = $sale->id;
        $lead->assigned_at = Carbon::now();
        $lead->save();

        CrmLeadActivity::create([
            'lead_id'  => $lead->id,
            'actor_id' => null,
            'type'     => 'assignment',
            'content'  => "Phân công qua WebApp (Telegram) cho: {$sale->name}",
        ]);

        if ($sale->telegram_id && $sale->telegram_bot_started && $this->notificationService->shouldNotify($sale, 'lead', 'assigned', 'telegram')) {
            $tpl = TelegramMessageTemplates::leadAssigned($lead);
            $this->notificationService->sendWithInlineKeyboard($sale->telegram_id, $tpl['text'], $tpl['keyboard']);
        }

        // In-app notification
        $this->inAppNotifService->notify($sale, 'lead_assigned', 'lead', 'assigned', [
            'title' => 'Lead mới được assign cho bạn',
            'body'  => ($lead->customer->full_name ?? 'N/A') . ' — ' . ($lead->lead_type === 'buy' ? 'Mua' : 'Thuê'),
            'notifiable_type' => CrmLead::class,
            'notifiable_id'   => $lead->id,
            'data'  => [
                'lead_id'       => $lead->id,
                'customer_name' => $lead->customer->full_name ?? '',
                'customer_phone' => $lead->customer->contact ?? '',
                'lead_type'     => $lead->lead_type,
            ],
        ]);

        return response()->json(['success' => true, 'sale_name' => $sale->name]);
    }
}
