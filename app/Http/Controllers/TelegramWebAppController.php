<?php

namespace App\Http\Controllers;

use App\Http\Traits\ValidatesTelegramInitData;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\PropertysInquiry;
use App\Models\Favourite;
use Carbon\Carbon;

use App\Models\Category;
use App\Models\LocationsWard;
use App\Models\LocationsStreet;
use App\Models\parameter;
use App\Models\AssignParameters;
use App\Models\OutdoorFacilities;
use App\Models\CrmHost;
use App\Models\PropertyImages;
use App\Models\PropertyLegalImage;
use App\Models\AssignedOutdoorFacilities;
use App\Models\CrmCustomer;
use App\Models\CrmDeal;
use App\Models\CrmLead;
use App\Models\Customer;
use App\Models\MarketPrice;
use App\Services\InAppNotificationService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\CrmLeadActivity;
use App\Models\CrmDealProductBooking;
use App\Enums\BookingStatus;
use App\Models\CrmDealCommission;
use App\Enums\CommissionStatus;
use App\Services\Telegram\TelegramMessageTemplates;

class TelegramWebAppController extends Controller
{
    use ValidatesTelegramInitData;
    public function index(Request $request)
    {
        // Get authenticated customer
        $customer = Auth::guard('webapp')->user();

        $stats = [
            'properties_count' => 0,
            'views_count' => 0,
            'reviews_count' => 0,
            'favourites_count' => 0,
            'views_count_week' => 0,
            'reviews_count_week' => 0,
            'favourites_count_week' => 0,
            'customers_count' => 0,
            'leads_count' => 0,
            'deals_count' => 0,
            'pending_count' => 0,
            'commission_total_fmt' => '0 đ',
            'commission_received_trieu' => 0,
            'commission_pending_trieu' => 0,
            'commission_deals' => 0,
        ];

        if ($customer) {
            // Count active (status=1) properties only
            $stats['properties_count'] = Property::where('added_by', $customer->id)->where('status', 1)->count();

            // Count total views of user's properties
            $stats['views_count'] = Property::where('added_by', $customer->id)->sum('total_click');

            // Count reviews/inquiries
            $stats['reviews_count'] = PropertysInquiry::whereIn('propertys_id', function ($query) use ($customer) {
                $query->select('id')->from('propertys')->where('added_by', $customer->id);
            })->count();

            // Count reviews/inquiries this week
            $stats['reviews_count_week'] = PropertysInquiry::whereIn('propertys_id', function ($query) use ($customer) {
                $query->select('id')->from('propertys')->where('added_by', $customer->id);
            })->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

            // Count favourites (properties interested by others or favourited by user)
            // Assuming we want to show how many people favourited this user's properties
            $stats['favourites_count'] = Favourite::whereIn('property_id', function ($query) use ($customer) {
                $query->select('id')->from('propertys')->where('added_by', $customer->id);
            })->count();

            // Count favourites this week
            $stats['favourites_count_week'] = Favourite::whereIn('property_id', function ($query) use ($customer) {
                $query->select('id')->from('propertys')->where('added_by', $customer->id);
            })->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

            // Distinct customers from broker's leads (for broker/bds_admin/admin)
            $stats['customers_count'] = CrmLead::where('user_id', $customer->id)
                ->whereNotNull('customer_id')
                ->distinct('customer_id')
                ->count('customer_id');

            // Leads assigned to this sale user (for sale/sale_admin)
            $stats['leads_count'] = CrmLead::where('sale_id', $customer->id)->count();

            // Deals linked to leads assigned to this sale user (for sale/sale_admin)
            $stats['deals_count'] = CrmDeal::whereHas('lead', function ($q) use ($customer) {
                $q->where('sale_id', $customer->id);
            })->count();

            // Pending properties awaiting approval (for bds_admin/admin)
            $stats['pending_count'] = Property::where('status', 0)->count();

            // Commission summary for profile quick view (sale/sale_admin/admin)
            $commBase = CrmDealCommission::whereHas('deal.lead', function ($q) use ($customer) {
                $q->where('sale_id', $customer->id)->orWhere('user_id', $customer->id);
            })->where('status', '!=', CommissionStatus::CANCELLED->value);
            $commReceived = (float)(clone $commBase)->where('status', CommissionStatus::COMPLETED->value)->sum('sale_commission');
            $commPending  = (float)(clone $commBase)->whereIn('status', [CommissionStatus::DEPOSITED->value, CommissionStatus::NOTARIZING->value])->sum('sale_commission');
            $commUpcoming = (float)(clone $commBase)->where('status', CommissionStatus::PENDING_DEPOSIT->value)->sum('sale_commission');
            $commTotal    = $commReceived + $commPending + $commUpcoming;
            $stats['commission_total_fmt']      = format_vnd($commTotal);
            $stats['commission_received_trieu'] = (int)round($commReceived / 1_000_000);
            $stats['commission_pending_trieu']  = (int)round(($commPending + $commUpcoming) / 1_000_000);
            $stats['commission_deals']          = (clone $commBase)->count();
        }

        // Market prices for the home page strip
        $marketPrices = MarketPrice::latestMonth()->orderByDesc('avg_price_m2')->take(3)->get();

        // First batch of properties for server-side render
        $properties = Property::with(['category', 'ward', 'propery_image'])
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $likedIds = $customer
            ? Favourite::where('user_id', $customer->id)->pluck('property_id')->toArray()
            : [];

        $categories = Category::where('status', 1)->orderBy('order')->get(['id', 'category']);

        $notifSettings = $customer ? $customer->getMergedNotifSettings() : Customer::DEFAULT_NOTIFICATION_SETTINGS;

        return view('webapp.layout', compact('customer', 'stats', 'properties', 'marketPrices', 'likedIds', 'categories', 'notifSettings'));
    //return view('frontend_dashboard', compact('customer', 'stats', 'properties'));
    }

    public function toggleFavourite(Request $request)
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $propertyId = $request->input('property_id');
        if (!$propertyId) {
            return response()->json(['success' => false, 'message' => 'Missing property_id'], 422);
        }

        $existing = Favourite::where('user_id', $customer->id)
            ->where('property_id', $propertyId)
            ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            Favourite::create(['user_id' => $customer->id, 'property_id' => $propertyId]);
            $liked = true;
        }

        return response()->json(['success' => true, 'liked' => $liked]);
    }

    public function likedProperties(Request $request)
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer) {
            return response()->json(['success' => false], 401);
        }

        $properties = Property::with(['category', 'ward'])
            ->whereIn('id', Favourite::where('user_id', $customer->id)->pluck('property_id'))
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($p) {
                return [
                    'id'            => $p->id,
                    'title'         => $p->title_by_address,
                    'price'         => $p->formatted_prices,
                    'location'      => $p->address_location,
                    'area'          => $p->area,
                    'category_name' => $p->category?->category,
                    'title_image'   => $p->title_image,
                ];
            });

        return response()->json(['success' => true, 'data' => $properties]);
    }

    public function myPropertiesApi(Request $request)
    {
        try {
            $customer = Auth::guard('webapp')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $statusFilter = $request->input('status', 'all');
            $search       = $request->input('search', '');
            $sort         = $request->input('sort', 'latest');

            // Counts (always from all properties, ignoring current filter)
            $activeCount  = Property::where('added_by', $customer->id)->where('status', 1)->count();
            $pendingCount = Property::where('added_by', $customer->id)->where('status', 0)->count();
            $hiddenCount  = Property::where('added_by', $customer->id)->where('status', 2)->count();
            $totalViews   = Property::where('added_by', $customer->id)->sum('total_click');

            // Main query
            $query = Property::where('propertys.added_by', $customer->id)
                ->with(['category', 'ward', 'street', 'parameters'])
                ->withCount(['favourite as favourite_count']);

            // Status filter
            if (in_array($statusFilter, ['0', '1', '2'])) {
                $query->where('propertys.status', (int) $statusFilter);
            }

            // Search filter
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('propertys.title', 'like', '%' . $search . '%')
                      ->orWhere('propertys.address', 'like', '%' . $search . '%');
                });
            }

            // Sort
            switch ($sort) {
                case 'oldest':
                    $query->orderBy('propertys.created_at', 'asc');
                    break;
                case 'views':
                    $query->orderBy('propertys.total_click', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('propertys.price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('propertys.price', 'desc');
                    break;
                default:
                    $query->orderBy('propertys.created_at', 'desc');
            }

            $properties = $query->get()->map(function ($p) {
                return [
                    'id'               => $p->id,
                    'title'            => $p->title ?: $p->title_by_address,
                    'price'            => $p->formatted_prices,
                    'price_m2'         => $p->formatted_price_m2,
                    'status'           => (int) $p->status,
                    'category_name'    => $p->category?->category ?? '',
                    'property_type'    => (int) $p->property_type,
                    'area'             => $p->area,
                    'rooms'            => $p->number_room,
                    'legal'            => $p->legal,
                    'direction'        => $p->direction,
                    'address_location' => $p->address_location,
                    'total_click'      => (int) $p->total_click,
                    'favourite_count'  => (int) $p->favourite_count,
                    'created_at'       => $p->created_at ? $p->created_at->format('d/m/Y') : '',
                    'title_image'      => $p->title_image,
                ];
            });

            return response()->json([
                'success' => true,
                'counts'  => [
                    'all'         => $activeCount + $pendingCount + $hiddenCount,
                    'active'      => $activeCount,
                    'pending'     => $pendingCount,
                    'hidden'      => $hiddenCount,
                    'total_views' => (int) $totalViews,
                ],
                'properties' => $properties,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống.'], 500);
        }
    }

    public function myCustomersApi(Request $request)
    {
        try {
            $customer = Auth::guard('webapp')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $search       = $request->input('search', '');
            $statusFilter = $request->input('status', 'all');

            $baseQuery = CrmLead::where('user_id', $customer->id)->whereNotNull('customer_id');

            // Counts (from full dataset, not filtered)
            $countNew       = (clone $baseQuery)->where('status', 'new')->count();
            $countContacted = (clone $baseQuery)->where('status', 'contacted')->count();
            $countConverted = (clone $baseQuery)->where('status', 'converted')->count();
            $countLost      = (clone $baseQuery)->whereIn('status', ['lost', 'bad-contact'])->count();

            // Main query with eager loading
            $query = (clone $baseQuery)->with([
                'customer',
                'deal',
                'activities' => function ($q) {
                    $q->orderBy('created_at', 'desc')->limit(5);
                },
            ]);

            // Status filter
            if ($statusFilter === 'new') {
                $query->where('status', 'new');
            } elseif ($statusFilter === 'contacted') {
                $query->where('status', 'contacted');
            } elseif ($statusFilter === 'converted') {
                $query->where('status', 'converted');
            } elseif ($statusFilter === 'lost') {
                $query->whereIn('status', ['lost', 'bad-contact']);
            }

            // Search by customer name or phone
            if (!empty($search)) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('full_name', 'like', '%' . $search . '%')
                      ->orWhere('contact', 'like', '%' . $search . '%');
                });
            }

            $leads = $query->orderBy('created_at', 'desc')->get();

            // Batch-resolve category and ward names to avoid N+1
            $allCatIds    = $leads->flatMap(fn($l) => $l->categories ?? [])->unique()->values();
            $allWardCodes = $leads->flatMap(fn($l) => $l->wards ?? [])->unique()->values();
            $catMap  = Category::whereIn('id', $allCatIds)->pluck('category', 'id');
            $wardMap = LocationsWard::whereIn('code', $allWardCodes)->pluck('full_name', 'code');

            $leadsData = $leads->map(function ($lead) use ($catMap, $wardMap) {
                $rawStatus  = $lead->getRawOriginal('status');
                $rawType    = $lead->getRawOriginal('lead_type');

                $categoryNames = collect($lead->categories ?? [])
                    ->map(fn($id) => $catMap[$id] ?? null)->filter()->values()->toArray();

                $wardNames = collect($lead->wards ?? [])
                    ->map(fn($c) => $wardMap[$c] ?? null)->filter()->values()->toArray();

                $budgetMin = (float) ($lead->demand_rate_min ?? 0);
                $budgetMax = (float) ($lead->demand_rate_max ?? 0);
                $budget = '';
                if ($budgetMin > 0 || $budgetMax > 0) {
                    $budget = ($budgetMin > 0 ? format_vnd($budgetMin) : '?')
                            . ' – '
                            . ($budgetMax > 0 ? format_vnd($budgetMax) : '?');
                }

                $activities = $lead->activities->map(function ($a) {
                    return [
                        'type'       => $a->type,
                        'type_label' => $a->getTypeLabel(),
                        'content'    => $a->content ?? '',
                        'created_at' => Carbon::parse($a->getRawOriginal('created_at'))->format('d/m'),
                    ];
                })->values()->toArray();

                $createdAt = Carbon::parse($lead->getRawOriginal('created_at'));

                return [
                    'id'             => $lead->id,
                    'status'         => $rawStatus,
                    'lead_type'      => $rawType,
                    'customer_name'  => optional($lead->customer)->full_name ?? 'Chưa rõ',
                    'customer_phone' => optional($lead->customer)->contact ?? '',
                    'categories'     => $categoryNames,
                    'wards'          => $wardNames,
                    'budget'         => $budget,
                    'note'           => $lead->note ?? '',
                    'has_deal'       => $lead->deal !== null,
                    'activities'     => $activities,
                    'created_at'     => $createdAt->format('d/m/Y'),
                    'created_diff'   => $createdAt->diffForHumans(),
                ];
            });

            return response()->json([
                'success' => true,
                'counts'  => [
                    'new'    => $countNew,
                    'care'   => $countContacted,
                    'deal'   => $countConverted,
                    'closed' => $countLost,
                    'all'    => $countNew + $countContacted + $countConverted + $countLost,
                ],
                'leads' => $leadsData,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống.'], 500);
        }
    }

    public function myLeadsApi(Request $request)
    {
        try {
            $customer = Auth::guard('webapp')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $search = trim($request->input('search', ''));
            $status = trim((string) $request->input('status', ''));
            $page   = (int) $request->input('page', 1);

            // Base query: leads where this user is the broker (user_id) or assigned sale (sale_id)
            $baseQuery = CrmLead::with(['customer'])
                ->where(function ($q) use ($customer) {
                    $q->where('sale_id', $customer->id)
                      ->orWhere('user_id', $customer->id);
                });

            // KPI counts — computed before search/status filters
            $kpi = [
                'new'       => (clone $baseQuery)->whereRaw("LOWER(status) = 'new'")->count(),
                'contacted' => (clone $baseQuery)->whereRaw("LOWER(status) = 'contacted'")->count(),
                'converted' => (clone $baseQuery)->whereRaw("LOWER(status) = 'converted'")->count(),
                'lost'      => (clone $baseQuery)->whereRaw("LOWER(status) = 'lost'")->count(),
            ];

            // Apply search filter
            if ($search !== '') {
                $baseQuery->where(function ($q) use ($search) {
                    $q->whereHas('customer', function ($cq) use ($search) {
                        $cq->where('full_name', 'LIKE', '%' . $search . '%')
                           ->orWhere('contact', 'LIKE', '%' . $search . '%');
                    })->orWhere('note', 'LIKE', '%' . $search . '%');
                });
            }

            // Apply status filter
            if ($status !== '') {
                $baseQuery->whereRaw("LOWER(status) = ?", [strtolower($status)]);
            }

            $paginator = $baseQuery->orderBy('created_at', 'desc')
                ->paginate(15, ['*'], 'page', $page);

            // Batch-resolve category and ward names
            $allCatIds    = $paginator->getCollection()->flatMap(fn($l) => $l->categories ?? [])->unique()->values();
            $allWardCodes = $paginator->getCollection()->flatMap(fn($l) => $l->wards ?? [])->unique()->values();
            $catMap  = \App\Models\Category::whereIn('id', $allCatIds)->pluck('category', 'id');
            $wardMap = \App\Models\LocationsWard::whereIn('code', $allWardCodes)->pluck('full_name', 'code');

            $leads = $paginator->getCollection()->map(function ($lead) use ($catMap, $wardMap) {
                $rawStatus = $lead->getRawOriginal('status');
                $rawType   = $lead->getRawOriginal('lead_type');

                $categoryNames = collect($lead->categories ?? [])
                    ->map(fn($id) => $catMap[$id] ?? null)->filter()->implode(', ');

                $wardNames = collect($lead->wards ?? [])
                    ->map(fn($c) => $wardMap[$c] ?? null)->filter()->implode(', ');

                $budgetMin = (float) ($lead->demand_rate_min ?? 0);
                $budgetMax = (float) ($lead->demand_rate_max ?? 0);
                $budget = '';
                if ($budgetMin > 0 || $budgetMax > 0) {
                    $budget = ($budgetMin > 0 ? format_vnd($budgetMin) : '?')
                            . ' – '
                            . ($budgetMax > 0 ? format_vnd($budgetMax) : '?');
                }

                $createdAt = Carbon::parse($lead->getRawOriginal('created_at'));

                return [
                    'id'             => $lead->id,
                    'status'         => $rawStatus,
                    'lead_type'      => $rawType === 'buy' ? 'Mua' : 'Thuê',
                    'purpose'        => $lead->purpose ?? '',
                    'customer_name'  => optional($lead->customer)->full_name ?? 'Khách vãng lai',
                    'customer_phone' => optional($lead->customer)->contact ?? '',
                    'categories'     => $categoryNames,
                    'wards'          => $wardNames,
                    'budget'         => $budget,
                    'note'           => $lead->note ?? '',
                    'source_note'    => $lead->source_note ?? '',
                    'has_deal'       => false, // avoid eager loading for performance
                    'created_at'     => $createdAt->format('d/m/Y'),
                    'created_diff'   => $createdAt->diffForHumans(),
                ];
            });

            return response()->json([
                'success'   => true,
                'kpi'       => $kpi,
                'leads'     => $leads,
                'total'     => $paginator->total(),
                'has_more'  => $paginator->hasMorePages(),
                'next_page' => $paginator->currentPage() + 1,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống.'], 500);
        }
    }

    public function myDealsApi(Request $request)
    {
        try {
            $customer = Auth::guard('webapp')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $search = trim($request->input('search', ''));
            $status = trim((string) $request->input('status', ''));
            $page   = (int) $request->input('page', 1);

            // Base query: deals where lead belongs to this customer (as broker or sale)
            $baseQuery = CrmDeal::whereHas('lead', function ($q) use ($customer) {
                $q->where('sale_id', $customer->id)
                  ->orWhere('user_id', $customer->id);
            });

            // KPI counts — before search/status filters
            $kpi = [
                'active'          => (clone $baseQuery)->whereRaw("LOWER(status) = 'open'")->count(),
                'negotiating'     => (clone $baseQuery)->whereRaw("LOWER(status) = 'negotiating'")->count(),
                'waiting_finance' => (clone $baseQuery)->whereRaw("LOWER(status) = 'waiting_finance'")->count(),
                'closed'          => (clone $baseQuery)->whereRaw("LOWER(status) = 'closed'")->count(),
            ];

            // Expected commission: sum from open/negotiating/waiting_finance deals
            $commissionTotal = CrmDeal::whereHas('lead', function ($q) use ($customer) {
                    $q->where('sale_id', $customer->id)->orWhere('user_id', $customer->id);
                })
                ->whereIn('status', ['open', 'negotiating', 'waiting_finance'])
                ->join('crm_deals_commissions', 'crm_deals.id', '=', 'crm_deals_commissions.deal_id')
                ->sum('crm_deals_commissions.sale_commission');
            $kpi['commission_expected'] = format_vnd((float) $commissionTotal);

            // Apply search filter
            if ($search !== '') {
                $baseQuery->whereHas('customer', function ($q) use ($search) {
                    $q->where('full_name', 'LIKE', '%' . $search . '%')
                      ->orWhere('contact', 'LIKE', '%' . $search . '%');
                });
            }

            // Apply status filter
            if ($status !== '') {
                $baseQuery->whereRaw("LOWER(status) = ?", [strtolower($status)]);
            }

            // Eager-load relationships
            $baseQuery->with([
                'customer',
                'lead',
                'products.property',
                'products.bookings',
                'commissions',
            ]);

            $paginator = $baseQuery->orderBy('created_at', 'desc')
                ->paginate(15, ['*'], 'page', $page);

            // Batch-resolve category and ward names from leads
            $allCatIds    = $paginator->getCollection()->flatMap(fn($d) => $d->lead?->categories ?? [])->unique()->values();
            $allWardCodes = $paginator->getCollection()->flatMap(fn($d) => $d->lead?->wards ?? [])->unique()->values();
            $catMap  = \App\Models\Category::whereIn('id', $allCatIds)->pluck('category', 'id');
            $wardMap = \App\Models\LocationsWard::whereIn('code', $allWardCodes)->pluck('full_name', 'code');

            $avatarColors = ['#6366f1', '#0ea5e9', '#14b8a6', '#f59e0b', '#ef4444', '#8b5cf6'];

            $productStatusMap = [
                'sent_info'         => ['key' => 'sent',        'label' => 'Đã gửi'],
                'sent_location'     => ['key' => 'sent',        'label' => 'Đã gửi'],
                'sent_legal'        => ['key' => 'sent',        'label' => 'Đã gửi'],
                'customer_feedback' => ['key' => 'sent',        'label' => 'Đã gửi'],
                'booking_created'   => ['key' => 'sent',        'label' => 'Đã gửi'],
                'viewed_success'    => ['key' => 'liked',       'label' => 'Ưng ý'],
                'viewed_failed'     => ['key' => 'disliked',    'label' => 'Không ưng'],
                'negotiating'       => ['key' => 'negotiating', 'label' => 'Đàm phán'],
                'waiting_finance'   => ['key' => 'waiting',     'label' => 'Chờ TC'],
            ];

            $viewedStatuses = ['booking_created', 'viewed_success', 'viewed_failed', 'negotiating', 'waiting_finance'];

            $deals = $paginator->getCollection()->map(function ($deal) use ($catMap, $wardMap, $avatarColors, $productStatusMap, $viewedStatuses) {
                $rawStatus = $deal->getRawOriginal('status');
                $lead      = $deal->lead;
                $cust      = $deal->customer;

                // Avatar initials + color
                $name    = optional($cust)->full_name ?? 'Khách';
                $words   = preg_split('/\s+/', trim($name));
                $initials = count($words) >= 2
                    ? mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[count($words) - 1], 0, 1))
                    : mb_strtoupper(mb_substr($name, 0, 2));
                $color = $avatarColors[$deal->id % count($avatarColors)];

                // Lead info
                $rawType       = $lead ? $lead->getRawOriginal('lead_type') : '';
                $categoryNames = collect($lead?->categories ?? [])
                    ->map(fn($id) => $catMap[$id] ?? null)->filter()->implode(', ');
                $wardNames = collect($lead?->wards ?? [])
                    ->map(fn($c) => $wardMap[$c] ?? null)->filter()->implode(', ');
                $budgetMin = (float) ($lead?->demand_rate_min ?? 0);
                $budgetMax = (float) ($lead?->demand_rate_max ?? 0);
                $budget = '';
                if ($budgetMin > 0 || $budgetMax > 0) {
                    $budget = ($budgetMin > 0 ? format_vnd($budgetMin) : '?')
                            . ' – '
                            . ($budgetMax > 0 ? format_vnd($budgetMax) : '?');
                }

                // Stage calculation
                $products      = $deal->products;
                $hasProducts   = $products->isNotEmpty();
                $hasViewed     = $products->some(fn($p) => in_array($p->getRawOriginal('status'), $viewedStatuses));
                $isNegotiating = $rawStatus === 'negotiating'
                    || $products->some(fn($p) => $p->getRawOriginal('status') === 'negotiating');
                $isClosed      = $rawStatus === 'closed';

                $stagesDone = [
                    true,           // Lead
                    true,           // Deal
                    $hasProducts,   // Chăm sóc
                    $hasViewed,     // Xem nhà
                    $isNegotiating, // Thương lượng
                    $isClosed,      // Chốt
                ];
                $currentStage = 1;
                foreach ($stagesDone as $i => $done) {
                    if ($done) $currentStage = $i + 1;
                }

                // Products list
                $mappedProducts = $products->map(function ($p) use ($productStatusMap) {
                    $rawPStatus  = $p->getRawOriginal('status');
                    $statusInfo  = $productStatusMap[$rawPStatus] ?? ['key' => 'sent', 'label' => 'Đã gửi'];
                    $prop        = $p->property;

                    $latestBooking = $p->bookings->sortByDesc(function ($b) {
                        return $b->booking_date->format('Y-m-d') . ($b->booking_time ?? '00:00');
                    })->first();

                    $bookingDisplay = null;
                    if ($latestBooking) {
                        $bookingDisplay = [
                            'date'   => Carbon::parse($latestBooking->booking_date)->format('d/m'),
                            'time'   => $latestBooking->booking_time ? substr($latestBooking->booking_time, 0, 5) : '',
                            'status' => $latestBooking->getRawOriginal('status') ?? '',
                        ];
                    }

                    return [
                        'id'             => $p->id,
                        'property_id'    => $p->property_id,
                        'title'          => optional($prop)->title
                            ?: (optional($prop)->title_by_address ?? ('BĐS #' . $p->property_id)),
                        'price'          => optional($prop)->formatted_prices ?? '',
                        'area'           => optional($prop)->area ? optional($prop)->area . ' m²' : '',
                        'bedrooms'       => optional($prop)->number_room,
                        'status_key'     => $statusInfo['key'],
                        'status_label'   => $statusInfo['label'],
                        'latest_booking' => $bookingDisplay,
                        'note'           => $p->note ?? '',
                    ];
                })->values();

                // Latest booking across all products for footer
                $allBookings         = $products->flatMap(fn($p) => $p->bookings);
                $latestBookingFooter = $allBookings->sortByDesc(function ($b) {
                    return $b->booking_date->format('Y-m-d') . ($b->booking_time ?? '00:00');
                })->first();
                $latestBookingDisplay = null;
                if ($latestBookingFooter) {
                    $latestBookingDisplay = 'Lịch xem: '
                        . Carbon::parse($latestBookingFooter->booking_date)->format('d/m')
                        . ($latestBookingFooter->booking_time ? ' ' . substr($latestBookingFooter->booking_time, 0, 5) : '');
                }

                $createdAt = Carbon::parse($deal->getRawOriginal('created_at'));

                return [
                    'id'                     => $deal->id,
                    'status'                 => $rawStatus,
                    'customer_name'          => $name,
                    'customer_phone'         => optional($cust)->contact ?? '',
                    'customer_telegram_id'   => optional($cust)->telegram_id ?? '',
                    'avatar_initials'        => $initials,
                    'avatar_color'           => $color,
                    'lead_type'              => $rawType === 'buy' ? 'Mua' : ($rawType === 'rent' ? 'Thuê' : ''),
                    'categories'             => $categoryNames,
                    'wards'                  => $wardNames,
                    'budget'                 => $budget,
                    'current_stage'          => $currentStage,
                    'stages_done'            => $stagesDone,
                    'created_at'             => $createdAt->format('d/m/Y'),
                    'created_diff'           => $createdAt->diffForHumans(),
                    'products_count'         => $products->count(),
                    'products'               => $mappedProducts,
                    'negotiation_info'       => $rawStatus === 'negotiating' ? ($deal->notes ?? null) : null,
                    'latest_booking_display' => $latestBookingDisplay,
                ];
            });

            return response()->json([
                'success'   => true,
                'kpi'       => $kpi,
                'deals'     => $deals,
                'total'     => $paginator->total(),
                'has_more'  => $paginator->hasMorePages(),
                'next_page' => $paginator->currentPage() + 1,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống.'], 500);
        }
    }

    public function tempui(Request $request)
    {
        $categories = Category::where('status', 1)->orderBy('order')->get(['id', 'category']);
        return view('frontend_dashboard_temp', compact('categories'));
    }

    public function propertyDetailJson(Request $request, $id)
    {
        $customer = Auth::guard('webapp')->user();

        $property = Property::with([
            'category', 'ward', 'street', 'parameters', 'assignfacilities.outdoorfacilities', 'host',
        ])->where('status', 1)->find($id);

        if (!$property) {
            return response()->json(['error' => 'Not found'], 404);
        }

        // Increment view count
        $property->increment('total_click');

        // All images: title + gallery
        $galleryBase = url('') . config('global.IMG_PATH') . config('global.PROPERTY_GALLERY_IMG_PATH');
        $galleryImages = $property->propery_image
            ->filter(fn($img) => $img->image)
            ->map(fn($img) => $galleryBase . $property->id . '/' . $img->image)
            ->values()->toArray();
        $allImages = $property->title_image
            ? array_merge([$property->title_image], $galleryImages)
            : $galleryImages;

        // Parameters keyed by id
        $areaParamId      = (int) config('global.area');
        $legalParamId     = (int) config('global.legal');
        $directionParamId = (int) config('global.direction');
        $area = $legal = $direction = '';
        $paramList = [];
        foreach ($property->parameters as $param) {
            $val = $param->pivot->value ?? '';
            if ((int)$param->id === $areaParamId)      $area      = $val;
            if ((int)$param->id === $legalParamId)     $legal     = $val;
            if ((int)$param->id === $directionParamId) $direction = $val;
            if ($val !== '' && $val !== null) {
                $paramList[] = ['id' => $param->id, 'name' => $param->name, 'value' => $val];
            }
        }

        // Facilities
        $facilities = [];
        foreach ($property->assignfacilities as $fac) {
            $of = $fac->outdoorfacilities;
            if ($of) {
                $facilities[] = [
                    'name'     => $of->name ?? '',
                    'icon'     => $of->image ?? '',
                    'distance' => $fac->distance ?? '',
                ];
            }
        }

        // Commission rate
        $commissionRate = 2;
        if ($property->price > 0 && $property->commission > 0) {
            $commissionRate = round(($property->commission / $property->price) * 100, 1);
        }

        // Host info — only for broker+
        $hostData = null;
        $brokerRoles = ['broker', 'sale', 'sale_admin', 'bds_admin', 'admin'];
        if ($customer && in_array($customer->role, $brokerRoles) && $property->host) {
            $phone = $property->host->contact ?? '';
            if (substr($phone, 0, 2) === '84') {
                $phone = '0' . substr($phone, 2);
            }
            $hostData = [
                'gender' => $property->host->gender ?? '1',
                'name'   => $property->host->name ?? '',
                'phone'  => $phone,
            ];
        }

        // Broker (person who listed it)
        $broker = null;
        if ($property->added_by) {
            $brokerUser = Customer::select('name', 'profile')->find($property->added_by);
            if ($brokerUser) {
                $parts = preg_split('/\s+/', trim($brokerUser->name ?? 'BK'));
                $initials = mb_strtoupper(mb_substr($parts[0], 0, 1) . (count($parts) > 1 ? mb_substr(end($parts), 0, 1) : ''));
                $broker = [
                    'name'     => $brokerUser->name ?? 'Môi giới',
                    'initials' => $initials ?: 'BK',
                    'avatar'   => $brokerUser->profile ?? null,
                    'role'     => 'eBroker · Đà Lạt BĐS',
                ];
            }
        }

        // Address
        $streetName = optional($property->street)->street_name ?? '';
        $wardName   = optional($property->ward)->name ?? '';

        // Similar properties — same category, exclude current
        $similar = Property::with(['category', 'ward'])
            ->where('status', 1)
            ->where('id', '!=', $property->id)
            ->where('category_id', $property->category_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(fn ($p) => [
                'id'    => $p->id,
                'title' => $p->title_by_address,
                'price' => $p->formatted_prices,
                'ward'  => optional($p->ward)->name ?? '',
                'image' => $p->title_image,
                'type'  => $p->category?->category ?? 'BĐS',
            ])->values()->toArray();

        return response()->json([
            'id'             => $property->id,
            'title'          => $property->title_by_address,
            'price'          => $property->formatted_prices,
            'priceM2'        => $property->formatted_price_m2 ?? '',
            'type'           => $property->category?->category ?? 'BĐS',
            'transactionType'=> $property->property_type == 1 ? 'rent' : 'sale',
            'area'           => $area ? $area . ' m²' : null,
            'room'           => $property->number_room ? $property->number_room . ' PN' : null,
            'legal'          => $legal,
            'direction'      => $direction,
            'addr'           => $streetName . ($wardName ? ', ' . $wardName : '') . ', Tp.Đà Lạt',
            'street'         => $streetName,
            'ward'           => $wardName,
            'houseNumber'    => $property->street_number ?? '',
            'rentduration'   => $property->rentduration,
            'description'    => $property->description,
            'latitude'       => $property->latitude,
            'longitude'      => $property->longitude,
            'views'          => $property->total_click,
            'images'         => $allImages,
            'commissionRate' => $commissionRate,
            'commission'     => $property->commission,
            'parameters'     => $paramList,
            'facilities'     => $facilities,
            'host'           => $hostData,
            'broker'         => $broker,
            'slug'           => $property->slug,
            'similar'        => $similar,
        ]);
    }

    public function nearbyProperties(Request $request)
    {
        $lat = clone $request->query('lat');
        $lng = clone $request->query('lng');
        $exclude_id = clone $request->query('exclude_id');

        $lat = (float) $lat;
        $lng = (float) $lng;

        if (!$lat || !$lng) {
            return response()->json(['success' => false, 'message' => 'Vui lòng cung cấp tọa độ hợp lệ']);
        }

        // Haversine formula to find nearby properties
        $haversine = "(6371 * acos(cos(radians($lat)) 
                        * cos(radians(latitude)) 
                        * cos(radians(longitude) - radians($lng)) 
                        + sin(radians($lat)) 
                        * sin(radians(latitude))))";

        $query = Property::select('id', 'title_by_address as title', 'category_id', 'latitude', 'longitude', 'title_image', 'price', 'type')
            ->selectRaw("{$haversine} AS distance")
            ->where('status', 1)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', '')
            ->where('longitude', '!=', '');

        if ($exclude_id) {
            $query->where('id', '!=', $exclude_id);
        }

        $nearby = $query->having('distance', '<', 50) // within 50km
            ->orderBy('distance', 'asc')
            ->limit(5)
            ->get();

        $data = $nearby->map(function ($p) {
            return [
                'id' => $p->id,
                'title' => $p->title,
                'price' => $p->formatted_prices, // Accessor from Property model
                'image' => $p->title_image ? url('') . config('global.IMG_PATH') . config('global.PROPERTY_TITLE_IMG_PATH') . $p->title_image : null,
                'type' => $p->category?->category ?? 'BĐS',
                'lat' => (float) $p->latitude,
                'lng' => (float) $p->longitude,
                'distance' => round($p->distance, 2) . ' km'
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function homeFeed(Request $request)
    {
        $page = (int) $request->get('page', 1);
        $categorySlug = $request->get('category_id'); // slug string from frontend chip
        $type = $request->get('type'); // null=all, '0'=buy, '1'=rent

        // Map frontend chip slugs to actual category IDs
        $categorySlugMap = [
            'dato'     => [1, 5, 8, 11], // Đất ở, Đất giấy tay, Đất ở phân quyền, Đất nông nghiệp
            'nha'      => [3, 4, 6],      // Nhà phân quyền, Nhà giấy tay, Nhà
            'bietthu'  => [9],            // Biệt thự
            'chungcu'  => [7],            // Chung cư
            'khachsan' => [2],            // Khách sạn
        ];

        $query = Property::with(['category', 'ward', 'propery_image'])
            ->where('status', 1)
            ->orderBy('created_at', 'desc');

        if ($categorySlug && isset($categorySlugMap[$categorySlug])) {
            $query->whereIn('category_id', $categorySlugMap[$categorySlug]);
        }
        if ($type !== null && $type !== '') {
            $query->where('property_type', (int) $type);
        }

        $paginator = $query->paginate(10, ['*'], 'page', $page);

        $galleryBase = url('') . config('global.IMG_PATH') . config('global.PROPERTY_GALLERY_IMG_PATH');

        $items = $paginator->map(function ($p) use ($galleryBase) {
            $galleryImages = $p->propery_image
                ->filter(fn($img) => $img->image)
                ->map(fn($img) => $galleryBase . $p->id . '/' . $img->image)
                ->values()
                ->toArray();

            return [
                'id'             => $p->id,
                'title'          => $p->title_by_address,
                'price'          => $p->formatted_prices,
                'location'       => $p->address_location,
                'area'           => $p->area,
                'legal'          => $p->legal,
                'number_room'    => $p->number_room,
                'total_click'    => $p->total_click,
                'title_image'    => $p->title_image ?: null,
                'category_name'  => $p->category?->category,
                'type_label'     => $p->type,
                'property_type'  => $p->property_type,
                'gallery_images' => $galleryImages,
                'slug'           => $p->slug,
                'added_by'       => $p->added_by,
            ];
        });

        return response()->json([
            'properties' => $items,
            'has_more'   => $paginator->hasMorePages(),
            'next_page'  => $paginator->currentPage() + 1,
        ]);
    }

    /**
     * Build the base search query with all filters applied.
     * Shared by searchResults() and searchResultsMap().
     */
    private function buildSearchQuery(Request $request)
    {
        $q = trim($request->get('q', ''));

        $query = Property::with(['category', 'ward', 'host'])
            ->where('status', 1);

        // Default sort
        $sort = $request->get('sort', 'latest');

        if ($q !== '') {
            $query->where(function ($qBuilder) use ($q) {
                $streetQ = trim(preg_replace('/^(Đường|đường|Đ\.|đ\.)\s+/iu', '', $q));
                $wardQ = trim(preg_replace('/^(Phường|phường|P\.|p\.|Xã|xã|X\.|x\.)\s+/iu', '', $q));

                $qBuilder->where('title', 'LIKE', '%' . $q . '%')
                         ->orWhere('address', 'LIKE', '%' . $q . '%')
                         ->orWhereHas('street', function ($sq) use ($streetQ) {
                             $sq->where('street_name', 'LIKE', '%' . $streetQ . '%');
                         })
                         ->orWhereHas('ward', function ($wq) use ($wardQ) {
                             $wq->where('full_name', 'LIKE', '%' . $wardQ . '%')
                                ->orWhere('name', 'LIKE', '%' . $wardQ . '%');
                         })
                         ->orWhereHas('category', function ($cq) use ($q) {
                             $cq->where('category', 'LIKE', '%' . $q . '%');
                         });
            });
        }

        // Filter: property_type (0=bán, 1=thuê)
        $type = $request->get('type');
        if ($type !== null && $type !== '') {
            if ($type === 'rent') $query->where('property_type', 1);
            else if ($type === 'sale') $query->where('property_type', 0);
            else $query->where('property_type', (int)$type);
        }

        // Filter: price range
        $priceLabel = $request->get('price');
        if ($priceLabel) {
            if ($priceLabel === 'Dưới 1 tỷ') {
                $query->where('price', '<', 1000000000);
            } elseif ($priceLabel === '1–2 tỷ') {
                $query->whereBetween('price', [1000000000, 2000000000]);
            } elseif ($priceLabel === '2–3 tỷ') {
                $query->whereBetween('price', [2000000000, 3000000000]);
            } elseif ($priceLabel === '3–5 tỷ') {
                $query->whereBetween('price', [3000000000, 5000000000]);
            } elseif ($priceLabel === '5–7 tỷ') {
                $query->whereBetween('price', [5000000000, 7000000000]);
            } elseif ($priceLabel === '7–10 tỷ') {
                $query->whereBetween('price', [7000000000, 10000000000]);
            } elseif ($priceLabel === 'Trên 10 tỷ') {
                $query->where('price', '>', 10000000000);
            }
        }

        // Filter: category name
        $categoryName = $request->get('categoryName');
        if ($categoryName) {
            $query->whereHas('category', function ($cq) use ($categoryName) {
                $cq->where('category', $categoryName);
            });
        }

        // Filter: area range (via assign_parameters)
        $areaRange = $request->get('area_range');
        if ($areaRange) {
            $areaParamId = (int) config('global.area');
            if ($areaRange === '1000+') {
                $query->whereHas('parameters', function ($pq) use ($areaParamId) {
                    $pq->where('parameters.id', $areaParamId)
                       ->whereRaw('CAST(assign_parameters.value AS DECIMAL(10,2)) >= 1000');
                });
            } else {
                $parts = explode('-', $areaRange);
                if (count($parts) === 2) {
                    $min = (float) $parts[0];
                    $max = (float) $parts[1];
                    $query->whereHas('parameters', function ($pq) use ($areaParamId, $min, $max) {
                        $pq->where('parameters.id', $areaParamId)
                           ->whereRaw('CAST(assign_parameters.value AS DECIMAL(10,2)) >= ?', [$min])
                           ->whereRaw('CAST(assign_parameters.value AS DECIMAL(10,2)) <= ?', [$max]);
                    });
                }
            }
        }

        // Filter: direction (via assign_parameters)
        $direction = $request->get('direction');
        if ($direction) {
            $dirParamId = (int) config('global.direction');
            $query->whereHas('parameters', function ($pq) use ($dirParamId, $direction) {
                $pq->where('parameters.id', $dirParamId)
                   ->where('assign_parameters.value', $direction);
            });
        }

        // Filter: legal (via assign_parameters)
        $legal = $request->get('legal');
        if ($legal) {
            $legalParamId = (int) config('global.legal');
            $query->whereHas('parameters', function ($pq) use ($legalParamId, $legal) {
                $pq->where('parameters.id', $legalParamId)
                   ->where('assign_parameters.value', 'LIKE', '%' . $legal . '%');
            });
        }

        // Filter: location (ward name from chip)
        $location = $request->get('location');
        if ($location) {
            $trimmedLocation = trim($location);
            $query->whereHas('ward', function ($wq) use ($trimmedLocation) {
                $wq->whereRaw('TRIM(full_name) = ?', [$trimmedLocation]);
            });
        }

        // Sort
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'area_asc':
            case 'area_desc':
                $areaParamId = (int) config('global.area');
                $query->select('propertys.*')
                    ->leftJoin('assign_parameters', function ($join) use ($areaParamId) {
                        $join->on('propertys.id', '=', 'assign_parameters.modal_id')
                            ->where('assign_parameters.parameter_id', $areaParamId);
                    })
                    ->addSelect(DB::raw('CAST(assign_parameters.value AS DECIMAL(10,2)) as area_value'))
                    ->orderBy('area_value', $sort === 'area_asc' ? 'asc' : 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query;
    }

    public function searchResults(Request $request)
    {
        $page = (int) $request->get('page', 1);

        $query = $this->buildSearchQuery($request);
        $query->with('propery_image');

        $paginator = $query->paginate(10, ['*'], 'page', $page);

        $galleryBase = url('') . config('global.IMG_PATH') . config('global.PROPERTY_GALLERY_IMG_PATH');

        $items = $paginator->map(function ($p) use ($galleryBase) {
            $galleryImages = $p->propery_image
                ->filter(fn($img) => $img->image)
                ->map(fn($img) => $galleryBase . $p->id . '/' . $img->image)
                ->values()
                ->toArray();

            return [
                'id'             => $p->id,
                'title'          => $p->title_by_address,
                'price'          => $p->formatted_prices,
                'location'       => $p->address_location,
                'area'           => $p->area,
                'legal'          => $p->legal,
                'number_room'    => $p->number_room,
                'total_click'    => $p->total_click,
                'title_image'    => $p->title_image ?: null,
                'category_name'  => $p->category?->category,
                'type_label'     => $p->type,
                'property_type'  => $p->property_type,
                'gallery_images' => $galleryImages,
                'created_at_diff' => \Carbon\Carbon::parse($p->created_at)->diffForHumans(),
                'added_by'       => $p->added_by,
                'host_phone'     => optional($p->host)->contact,
                'latitude'       => $p->latitude ? (float) $p->latitude : null,
                'longitude'      => $p->longitude ? (float) $p->longitude : null,
            ];
        });

        return response()->json([
            'success' => true,
            'properties' => $items,
            'total'      => $paginator->total(),
            'has_more'   => $paginator->hasMorePages(),
            'next_page'  => $paginator->currentPage() + 1,
        ]);
    }

    /**
     * Return all matching properties with coordinates for the map view (max 200).
     */
    public function searchResultsMap(Request $request)
    {
        $query = $this->buildSearchQuery($request);
        $totalAll = (clone $query)->count();

        $query->whereNotNull('latitude')
              ->whereNotNull('longitude')
              ->where('latitude', '!=', '')
              ->where('longitude', '!=', '');

        $items = $query->limit(200)->get()->map(function ($p) {
            return [
                'id'             => $p->id,
                'title'          => $p->title_by_address,
                'price'          => $p->formatted_prices,
                'price_raw'      => $p->price,
                'location'       => $p->address_location,
                'area'           => $p->area,
                'legal'          => $p->legal,
                'category_name'  => $p->category?->category,
                'type_label'     => $p->type,
                'property_type'  => $p->property_type,
                'title_image'    => $p->title_image ?: null,
                'latitude'       => (float) $p->latitude,
                'longitude'      => (float) $p->longitude,
                'number_room'    => $p->number_room,
                'host_phone'     => optional($p->host)->contact,
            ];
        });

        return response()->json([
            'success'          => true,
            'properties'       => $items,
            'total'            => $totalAll,
            'total_with_coords' => $items->count(),
        ]);
    }

    public function searchSuggestions(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $results = [];

        // Bỏ tiền tố để tìm kiếm chính xác hơn trong CSDL
        $streetQ = trim(preg_replace('/^(Đường|đường|Đ\.|đ\.)\s+/iu', '', $q));
        $wardQ = trim(preg_replace('/^(Phường|phường|P\.|p\.|Xã|xã|X\.|x\.)\s+/iu', '', $q));

        // 1. Tìm đường (Street)
        $streets = LocationsStreet::where('district_code', config('location.district_code'))
            ->where('street_name', 'LIKE', '%' . $streetQ . '%')
            ->limit(3)
            ->get();
        foreach ($streets as $s) {
            $results[] = [
                'type' => 'street',
                'title' => 'Đường ' . $s->street_name,
                'sub' => 'Đà Lạt',
                'query' => 'Đường ' . $s->street_name,
                'icon' => 'street'
            ];
        }

        // 2. Tìm phường (Ward)
        $wards = LocationsWard::where('district_code', config('location.district_code'))
            ->where(function ($wBuilder) use ($wardQ) {
                $wBuilder->where('full_name', 'LIKE', '%' . $wardQ . '%')
                         ->orWhere('name', 'LIKE', '%' . $wardQ . '%');
            })
            ->limit(2)
            ->get();
        foreach ($wards as $w) {
            $results[] = [
                'type' => 'ward',
                'title' => $w->full_name,
                'sub' => 'Khu vực',
                'query' => $w->full_name,
                'icon' => 'area'
            ];
        }

        // 3. Tìm BĐS (Property title or address)
        $props = Property::with('category')->where('status', 1)
            ->where(function ($query) use ($q) {
                $query->where('title', 'LIKE', '%' . $q . '%')
                      ->orWhere('address', 'LIKE', '%' . $q . '%');
            })
            ->limit(4)
            ->get();
        
        foreach ($props as $p) {
            $results[] = [
                'type' => 'property',
                'title' => mb_substr($p->title_by_address, 0, 45) . '...',
                'sub' => $p->formatted_prices . ' · ' . ($p->category ? $p->category->category : 'BĐS'),
                'query' => $p->title_by_address,
                'id' => $p->id,
                'icon' => 'property'
            ];
        }

        return response()->json(['success' => true, 'data' => $results]);
    }

    // Task 8: Search Leads API
    public function searchLeads(Request $request)
    {
        $q = trim($request->get('q', ''));
        $status = $request->get('status', '');
        $page = (int) $request->get('page', 1);

        $query = CrmLead::with(['customer', 'sale'])
            ->orderBy('created_at', 'desc');

        if ($q !== '') {
            $query->where(function ($qb) use ($q) {
                $qb->where('note', 'LIKE', '%' . $q . '%')
                    ->orWhere('source_note', 'LIKE', '%' . $q . '%')
                    ->orWhereHas('customer', function ($cq) use ($q) {
                        $cq->where('name', 'LIKE', '%' . $q . '%')
                            ->orWhere('phone', 'LIKE', '%' . $q . '%');
                    });
            });
        }

        if ($status !== '' && $status !== null) {
            $query->whereRaw("LOWER(REPLACE(status, ' ', '-')) = ?", [strtolower($status)]);
        }

        $paginator = $query->paginate(15, ['*'], 'page', $page);

        $items = $paginator->map(function ($lead) {
            return [
                'id'           => $lead->id,
                'customer_name' => optional($lead->customer)->name ?? 'Chưa rõ',
                'customer_phone' => optional($lead->customer)->phone ?? '',
                'lead_type'    => $lead->getRawOriginal('lead_type') === 'buy' ? 'Mua' : 'Thuê',
                'status'       => $lead->getRawOriginal('status'),
                'status_label' => $lead->status,
                'categories'   => $lead->categories,
                'wards'        => $lead->wards,
                'budget_min'   => $lead->demand_rate_min,
                'budget_max'   => $lead->demand_rate_max,
                'note'         => $lead->note,
                'sale_name'    => optional($lead->sale)->name ?? '',
                'created_at_diff' => \Carbon\Carbon::parse($lead->getRawOriginal('created_at'))->diffForHumans(),
            ];
        });

        return response()->json([
            'success'    => true,
            'leads'      => $items,
            'total'      => $paginator->total(),
            'has_more'   => $paginator->hasMorePages(),
            'next_page'  => $paginator->currentPage() + 1,
        ]);
    }

    // Task 11: Search Areas API
    public function searchAreas(Request $request)
    {
        $wards = LocationsWard::where('district_code', config('location.district_code'))->get();

        $wardStats = Property::where('status', 1)
            ->selectRaw('ward_code, count(*) as count_bds, AVG(price) as avg_price')
            ->groupBy('ward_code')
            ->get()
            ->keyBy('ward_code');

        $data = $wards->map(function ($w) use ($wardStats) {
            $stats = $wardStats[$w->code] ?? null;
            $avgPrice = null;
            if ($stats && $stats->avg_price > 0) {
                $avg = $stats->avg_price;
                $ty = 1000000000;
                $trieu = 1000000;
                if ($avg >= $ty) {
                    $avgPrice = number_format($avg / $ty, 1) . ' tỷ';
                } elseif ($avg > 0) {
                    $avgPrice = number_format($avg / $trieu, 0) . ' triệu';
                }
            }
            return [
                'code'      => $w->code,
                'name'      => $w->full_name,
                'count_bds' => $stats ? $stats->count_bds : 0,
                'avg_price' => $avgPrice,
            ];
        })->sortByDesc('count_bds')->values();

        return response()->json(['success' => true, 'areas' => $data]);
    }

    public function profile(Request $request)
    {
        $customer = Auth::guard('webapp')->user();
        return view('frontend_dashboard_myprofile', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('webapp')->user();

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['nullable', 'email', 'max:255'],
            'mobile'           => ['nullable', 'string', 'regex:/^(0[3-9][0-9]{8})$/'],
            'zalo'             => ['nullable', 'string', 'regex:/^(0[3-9][0-9]{8})$/'],
            'bio'              => ['nullable', 'string', 'max:1000'],
            'facebook_link'    => ['nullable', 'url', 'max:255'],
            'years_experience' => ['nullable', 'integer', 'min:0', 'max:50'],
            'work_area'        => ['nullable', 'string', 'max:255'],
            'specialization'   => ['nullable', 'string', 'max:255'],
        ], [
            'name.required'          => 'Họ và tên không được để trống.',
            'email.email'            => 'Email không đúng định dạng.',
            'mobile.regex'           => 'SĐT phải là số VN 10 chữ số (bắt đầu 03-09).',
            'zalo.regex'             => 'SĐT Zalo phải là số VN 10 chữ số (bắt đầu 03-09).',
            'facebook_link.url'      => 'Link Facebook không đúng định dạng URL.',
            'years_experience.integer' => 'Số năm kinh nghiệm phải là số nguyên.',
        ]);

        $customer->fill($validated)->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật hồ sơ thành công!',
                'customer' => [
                    'name'             => $customer->name,
                    'email'            => $customer->email,
                    'mobile'           => $customer->mobile,
                    'zalo'             => $customer->zalo,
                    'bio'              => $customer->bio,
                    'facebook_link'    => $customer->facebook_link,
                    'years_experience' => $customer->years_experience,
                    'work_area'        => $customer->work_area,
                    'specialization'   => $customer->specialization,
                ],
            ]);
        }

        return redirect()->route('webapp.profile')->with('success', 'Cập nhật hồ sơ thành công!');
    }

    public function updateAvatar(Request $request)
    {
        $customer = Auth::guard('webapp')->user();

        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ], [
            'avatar.required' => 'Vui lòng chọn ảnh.',
            'avatar.image'    => 'File phải là ảnh (jpg, png, gif...).',
            'avatar.max'      => 'Ảnh không được vượt quá 2MB.',
        ]);

        $file = $request->file('avatar');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $destinationPath = public_path('images') . config('global.USER_IMG_PATH');

        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $file->move($destinationPath, $filename);

        // Lưu raw filename (accessor sẽ build full URL)
        $customer->setRawAttributes(array_merge($customer->getAttributes(), ['profile' => $filename]));
        $customer->save();

        return response()->json([
            'success' => true,
            'url'     => url('images' . config('global.USER_IMG_PATH') . $filename),
        ]);
    }

    public function messages(Request $request)
    {
        return view('frontend_dashboard_messages');
    }

    public function listings(Request $request)
    {
        try {
            $customer = Auth::guard('webapp')->user();

            // Validate input params
            $data = $request->validate([
                'search' => ['nullable', 'string', 'max:255'],
                'sort' => ['nullable', 'string'],
                'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            ]);

            $perPage = $data['per_page'] ?? 10;

            $query = Property::query()->with(['category']);

            // Select columns. Ensure we select 'propertys.*' to avoid ambiguity when joining
            $query->select('propertys.*');

            if ($customer) {
                $query->where('propertys.added_by', $customer->id);
            }

            if (!empty($data['search'])) {
                $search = $data['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('propertys.title', 'like', '%' . $search . '%')
                        ->orWhere('propertys.address', 'like', '%' . $search . '%');
                });
            }

            if (!empty($data['sort'])) {
                switch ($data['sort']) {
                    case 'oldest':
                        $query->orderBy('propertys.created_at', 'asc');
                        break;
                    case 'views':
                        $query->orderBy('propertys.total_click', 'desc');
                        break;
                    case 'price_asc':
                        $query->orderBy('propertys.price', 'asc');
                        break;
                    case 'price_desc':
                        $query->orderBy('propertys.price', 'desc');
                        break;
                    case 'area_asc':
                    case 'area_desc':
                        $areaParamId = config('global.area');
                        $query->leftJoin('assign_parameters', function ($join) use ($areaParamId) {
                            $join->on('propertys.id', '=', 'assign_parameters.modal_id')
                                ->where('assign_parameters.parameter_id', $areaParamId);
                        })
                            ->addSelect(DB::raw('CAST(assign_parameters.value AS DECIMAL(10,2)) as area_value'))
                            ->orderBy('area_value', $data['sort'] === 'area_asc' ? 'asc' : 'desc');
                        break;
                    case 'latest':
                    default:
                        $query->orderBy('propertys.created_at', 'desc');
                }
            }
            else {
                $query->orderBy('propertys.created_at', 'desc');
            }

            $properties = $query->paginate($perPage)->appends($request->query());

            if ($request->ajax()) {
                return view('frontends.components.dashboard_listings_items', compact('properties'))->render();
            }

            return view('frontend_dashboard_listings', compact('customer', 'properties'));
        }
        catch (\Illuminate\Validation\ValidationException $ve) {
            if ($request->ajax()) {
                return response()->json(['error' => $ve->errors()], 422);
            }
            return redirect()->back()->withErrors($ve->errors());
        }
        catch (\Exception $e) {
            report($e);
            if ($request->ajax()) {
                return response()->json(['error' => 'Server Error'], 500);
            }
            return redirect()->back()->with('error', 'Không thể tải danh sách tin đăng, vui lòng thử lại sau.');
        }
    }

    public function feed(Request $request)
    {
        try {
            $customer = Auth::guard('webapp')->user();

            $perPage = 10;
            // Get properties with status = 1 (Active) and sort by newest
            $query = Property::with(['category', 'host'])->where('status', 1)->orderBy('created_at', 'desc');

            $properties = $query->paginate($perPage);

            if ($request->ajax()) {
                return view('frontends.components.dashboard_feed_items', compact('properties', 'customer'))->render();
            }

            return view('frontend_dashboard_feed', compact('customer', 'properties'));
        }
        catch (\Exception $e) {
            Log::error($e);
            if ($request->ajax()) {
                return response()->json(['error' => 'Server Error'], 500);
            }
            return redirect()->route('webapp')->with('error', 'Không thể tải luồng tin.');
        }
    }

    public function destroy($id)
    {
        try {
            $customer = Auth::guard('webapp')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $property = Property::where('id', $id)->where('added_by', $customer->id)->first();
            if (!$property) {
                return response()->json(['success' => false, 'message' => 'Tin đăng không tồn tại hoặc bạn không có quyền xóa.'], 404);
            }

            // Optional: Soft delete or hard delete. Using delete() which is likely standard delete unless SoftDeletes trait is used.
            $property->delete();

            return response()->json(['success' => true, 'message' => 'Đã xóa tin đăng thành công.']);
        }
        catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống.'], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $customer = Auth::guard('webapp')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $property = Property::where('id', $id)->where('added_by', $customer->id)->first();
            if (!$property) {
                return response()->json(['success' => false, 'message' => 'Tin đăng không tồn tại.'], 404);
            }

            // Toggle status. Assuming 1 = Show, 0 = Hide/Pending.
            // Adjust logic if you have different status codes (e.g., 2 for hidden).
            // Based on addListing, status 0 is 'Pending approval'.
            // If we want a simple Hide/Show, we might need a separate column 'is_hidden' or reuse status if allowed.
            // Let's assume we toggle between 1 (Active) and 2 (Hidden).
            // Or if 0 is Pending, 1 is Active. If user hides it, maybe 2?
            // Let's check current status usage.
            // If currently 1, set to 2 (Hidden). If 2, set to 1.
            // If 0 (Pending), maybe don't allow toggle or toggle to 2.

            $newStatus = ($property->status == 1) ? 2 : 1;
            if ($property->status == 0) {
                // If pending, maybe just toggle to hidden (2) ?
                // For now let's assume 1 <-> 2 toggle.
                $newStatus = 2;
            }

            // If the requirement is just "Hide/Show", maybe we use a dedicated flag or status.
            // Let's stick to 1 (Active) and 2 (Hidden/Disabled) for user-controlled toggle.
            // 0 is usually "Pending Admin Approval".

            $property->status = $newStatus;
            $property->save();

            return response()->json(['success' => true, 'status' => $newStatus, 'message' => 'Cập nhật trạng thái thành công.']);
        }
        catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống.'], 500);
        }
    }

    public function agents(Request $request)
    {
        return view('frontend_dashboard_agents');
    }

    public function bookings(Request $request)
    {
        return view('frontend_dashboard_bookings');
    }

    public function apiGetBookings(Request $request)
    {
        $customer = Auth::guard('webapp')->user();

        $bookings = CrmDealProductBooking::whereHas('crmDealProduct.deal.lead', function ($q) use ($customer) {
            $q->where('sale_id', $customer->id)->orWhere('user_id', $customer->id);
        })
            ->with(['crmDealProduct.property', 'crmDealProduct.deal.customer'])
            ->orderBy('booking_date', 'asc')
            ->orderBy('booking_time', 'asc')
            ->get()
            ->map(function ($booking) {
                $dealProduct = $booking->crmDealProduct;
                $property = $dealProduct->property ?? null;
                $crmCustomer = $dealProduct->deal->customer ?? null;

                return [
                    'id' => $booking->id,
                    'booking_date' => $booking->booking_date ? $booking->booking_date->format('Y-m-d') : null,
                    'booking_time' => $booking->booking_time ? substr($booking->booking_time, 0, 5) : null,
                    'status' => $booking->status->value,
                    'customer_feedback' => $booking->customer_feedback,
                    'internal_note' => $booking->internal_note,
                    'property_title' => $property ? $property->title : 'Bất động sản',
                    'customer_name' => $crmCustomer ? $crmCustomer->full_name : 'Khách hàng',
                    'customer_phone' => $crmCustomer ? $crmCustomer->contact : null,
                    'deal_product_id' => $dealProduct->id,
                ];
            });

        $today = now()->toDateString();
        $weekEnd = now()->endOfWeek()->toDateString();
        $monthStr = now()->format('Y-m');

        $activeStatuses = [BookingStatus::SCHEDULED->value, BookingStatus::RESCHEDULED->value];

        $stats = [
            'today' => $bookings->filter(fn($b) => $b['booking_date'] === $today && in_array($b['status'], $activeStatuses))->count(),
            'this_week' => $bookings->filter(fn($b) => $b['booking_date'] >= $today && $b['booking_date'] <= $weekEnd && in_array($b['status'], $activeStatuses))->count(),
            'needs_update' => $bookings->filter(fn($b) => $b['booking_date'] < $today && in_array($b['status'], $activeStatuses))->count(),
            'this_month' => $bookings->filter(fn($b) => str_starts_with($b['booking_date'] ?? '', $monthStr))->count(),
        ];

        return response()->json(['success' => true, 'bookings' => $bookings, 'stats' => $stats]);
    }

    public function apiUpdateBookingResult(Request $request, $id)
    {
        $customer = Auth::guard('webapp')->user();

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:completed_success,completed_negotiating,completed_failed,cancelled',
            'customer_feedback' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $booking = CrmDealProductBooking::whereHas('crmDealProduct.deal.lead', function ($q) use ($customer) {
            $q->where('sale_id', $customer->id)->orWhere('user_id', $customer->id);
        })->find($id);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy lịch hẹn'], 404);
        }

        $booking->status = BookingStatus::from($request->status);
        if ($request->filled('customer_feedback')) {
            $booking->customer_feedback = $request->customer_feedback;
        }
        $booking->save();

        // In-app notification for booking result
        $booking->load('crmDealProduct.deal.lead');
        $lead = $booking->crmDealProduct?->deal?->lead;
        if ($lead) {
            $statusLabels = [
                'completed_success'      => 'Ưng ý',
                'completed_negotiating'  => 'Đang thương lượng',
                'completed_failed'       => 'Không ưng',
                'cancelled'              => 'Đã hủy',
            ];
            $statusLabel = $statusLabels[$request->status] ?? $request->status;
            $propTitle = $booking->crmDealProduct?->property?->title ?? 'BĐS';
            $inAppService = app(InAppNotificationService::class);

            // Notify sale assigned to this lead
            if ($lead->sale_id) {
                $sale = Customer::find($lead->sale_id);
                if ($sale) {
                    $inAppService->notify($sale, 'booking_result', 'booking', 'result', [
                        'title' => 'Kết quả xem nhà: ' . $statusLabel,
                        'body'  => $propTitle . ' — ' . ($lead->customer?->full_name ?? 'Khách'),
                        'notifiable_type' => CrmDealProductBooking::class,
                        'notifiable_id'   => $booking->id,
                        'actor_id'        => $customer->id,
                        'data'  => ['booking_id' => $booking->id, 'status' => $request->status, 'property_title' => $propTitle],
                    ]);
                }
            }
            // Notify broker who created the lead
            if ($lead->user_id && $lead->user_id !== $lead->sale_id) {
                $broker = Customer::find($lead->user_id);
                if ($broker) {
                    $inAppService->notify($broker, 'booking_result', 'booking', 'result', [
                        'title' => 'Kết quả xem nhà: ' . $statusLabel,
                        'body'  => $propTitle . ' — ' . ($lead->customer?->full_name ?? 'Khách'),
                        'notifiable_type' => CrmDealProductBooking::class,
                        'notifiable_id'   => $booking->id,
                        'actor_id'        => $customer->id,
                        'data'  => ['booking_id' => $booking->id, 'status' => $request->status, 'property_title' => $propTitle],
                    ]);
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Đã cập nhật kết quả']);
    }

    public function apiRescheduleBooking(Request $request, $id)
    {
        $customer = Auth::guard('webapp')->user();

        $validator = Validator::make($request->all(), [
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $booking = CrmDealProductBooking::whereHas('crmDealProduct.deal.lead', function ($q) use ($customer) {
            $q->where('sale_id', $customer->id)->orWhere('user_id', $customer->id);
        })->find($id);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy lịch hẹn'], 404);
        }

        $booking->booking_date = $request->booking_date;
        $booking->booking_time = $request->booking_time;
        $booking->status = BookingStatus::RESCHEDULED;
        $booking->save();

        // In-app notification for reschedule
        $this->notifyBookingChanged($booking, $customer, 'Dời lịch xem nhà', 'Lịch mới: ' . $request->booking_date . ' ' . $request->booking_time);

        return response()->json(['success' => true, 'message' => 'Đã dời lịch thành công']);
    }

    public function apiCancelBooking(Request $request, $id)
    {
        $customer = Auth::guard('webapp')->user();

        $booking = CrmDealProductBooking::whereHas('crmDealProduct.deal.lead', function ($q) use ($customer) {
            $q->where('sale_id', $customer->id)->orWhere('user_id', $customer->id);
        })->find($id);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy lịch hẹn'], 404);
        }

        $booking->status = BookingStatus::CANCELLED;
        $booking->save();

        // In-app notification for cancel
        $this->notifyBookingChanged($booking, $customer, 'Lịch xem nhà đã bị hủy', null);

        return response()->json(['success' => true, 'message' => 'Đã huỷ lịch hẹn']);
    }

    /**
     * Helper: send in-app notification when booking is rescheduled or cancelled.
     */
    private function notifyBookingChanged(CrmDealProductBooking $booking, $actor, string $title, ?string $extraBody): void
    {
        $booking->load('crmDealProduct.deal.lead');
        $lead = $booking->crmDealProduct?->deal?->lead;
        if (!$lead) return;

        $propTitle = $booking->crmDealProduct?->property?->title ?? 'BĐS';
        $body = $propTitle . ($extraBody ? ' — ' . $extraBody : '');
        $inAppService = app(InAppNotificationService::class);

        // Notify sale + broker involved
        $recipientIds = array_unique(array_filter([$lead->sale_id, $lead->user_id]));
        foreach ($recipientIds as $rid) {
            if ($rid == $actor->id) continue; // don't notify the actor
            $recipient = Customer::find($rid);
            if ($recipient) {
                $inAppService->notify($recipient, 'booking_changed', 'booking', 'result', [
                    'title' => $title,
                    'body'  => $body,
                    'notifiable_type' => CrmDealProductBooking::class,
                    'notifiable_id'   => $booking->id,
                    'actor_id'        => $actor->id,
                    'data'  => ['booking_id' => $booking->id, 'property_title' => $propTitle],
                ]);
            }
        }
    }

    public function reviews(Request $request)
    {
        return view('frontend_dashboard_reviews');
    }

    public function addListing(Request $request)
    {
        // 1. Property Types (Categories)
        $dbCategories = Category::where('status', '1')->orderBy('order', 'asc')->get();
        $propertyTypes = $dbCategories->map(function ($cat) {
            $isHouse = !Str::contains(Str::lower($cat->category), ['đất', 'land']);

            // Parse parameter_types from the category
            $parameterIds = [];
            if ($cat->parameter_types) {
                $parameterIds = array_map('intval', explode(',', $cat->parameter_types));
            }

            // Icon mapping (basic heuristic)
            $icon = 'fa-house';
            $lowerName = Str::lower($cat->category);
            if (Str::contains($lowerName, 'biệt thự'))
                $icon = 'fa-hotel';
            elseif (Str::contains($lowerName, 'khách sạn'))
                $icon = 'fa-bell-concierge';
            elseif (Str::contains($lowerName, 'chung cư'))
                $icon = 'fa-building';
            elseif (Str::contains($lowerName, 'đất'))
                $icon = 'fa-map-location-dot';

            return [
            'id' => $cat->id, // Use DB ID
            'name' => $cat->category,
            'icon' => $icon,
            'isHouse' => $isHouse,
            'parameter_ids' => $parameterIds // Add parameter IDs for this category
            ];
        });

        // 2. Wards
        $districtCode = config('location.district_code');
        $wards = LocationsWard::select('code', 'full_name')
            ->where('district_code', $districtCode)
            ->orderByRaw("CASE
                            WHEN full_name LIKE 'phường%' THEN 1
                            WHEN full_name LIKE 'Xã%' THEN 2
                            ELSE 3 END,
                          CAST(SUBSTRING_INDEX(full_name, ' ', -1) AS UNSIGNED),
                          full_name")
            ->get()
            ->map(function ($w) {
            return [
            'id' => $w->code,
            'name' => $w->full_name,
            'icon' => 'fa-map-pin'
            ];
        });

        // 3. Streets
        $streets = LocationsStreet::select('code', 'street_name')
            ->where('district_code', $districtCode)
            ->get()
            ->map(function ($s) {
            return [
            'id' => $s->code,
            'name' => $s->street_name
            ];
        });

        // 4. Parameters and Assign Parameters
        $parameters = parameter::with('assigned_parameter')->get()->map(function ($param) {
            return [
            'id' => $param->id,
            'name' => $param->name,
            'type_of_parameter' => $param->type_of_parameter,
            'type_values' => $param->type_values
            ];
        });

        $assignParameters = AssignParameters::all()->map(function ($ap) {
            return [
            'id' => $ap->id,
            'property_id' => $ap->property_id,
            'parameter_id' => $ap->parameter_id,
            'value' => $ap->value
            ];
        });

        // 5. Outdoor Facilities
        $facilities = OutdoorFacilities::all();

        // 6. Hardcoded Data (Moved from View)
        $legalTypes = [
            ['value' => 'Sổ xây dựng', 'name' => 'Sổ xây dựng', 'icon' => 'fa-file-contract'],
            ['value' => 'Sổ nông nghiệp', 'name' => 'Sổ nông nghiệp', 'icon' => 'fa-file-contract'],
            ['value' => 'Sổ phân quyền xây dựng', 'name' => 'Sổ phân quyền xây dựng', 'icon' => 'fa-file-signature'],
            ['value' => 'Sổ phân quyền nông nghiệp', 'name' => 'Sổ phân quyền nông nghiệp', 'icon' => 'fa-file-signature'],
            ['value' => 'Giấy tay', 'name' => 'Giấy tay / Vi bằng', 'icon' => 'fa-file-alt']
        ];

        $directions = ['Đông', 'Tây', 'Nam', 'Bắc', 'Đông Nam', 'Đông Bắc', 'Tây Nam', 'Tây Bắc'];

        $commissionRates = [1, 1.5, 2, 2.5, 3];

        return view('frontend_dashboard_add_listing', compact('propertyTypes', 'wards', 'streets', 'parameters', 'assignParameters', 'facilities', 'legalTypes', 'directions', 'commissionRates'));
    }

    public function submitForm(Request $request)
    {
        try {
            DB::beginTransaction();

            // Check Auth
            $customer = Auth::guard('webapp')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập lại.'], 401);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'type' => 'required',
                'transactionType' => 'required',
                'rentduration' => 'nullable|string',
                'ward' => 'required',
                'price' => 'required|numeric|min:0',
                'area' => 'required|numeric|min:0',
                'commissionRate' => 'required|numeric',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'avatar' => 'required|image|max:5120', // Max 5MB to match frontend
            ], [
                'type.required' => 'Vui lòng chọn loại bất động sản.',
                'transactionType.required' => 'Vui lòng chọn hình thức giao dịch.',
                'ward.required' => 'Vui lòng chọn khu vực (Phường/Xã).',
                'price.required' => 'Vui lòng nhập mức giá.',
                'area.required' => 'Vui lòng nhập diện tích.',
                'commissionRate.required' => 'Vui lòng chọn mức hoa hồng.',
                'avatar.required' => 'Vui lòng tải lên ảnh đại diện.',
                'avatar.max' => 'Ảnh đại diện không được vượt quá 5MB.',
                'avatar.image' => 'File tải lên phải là hình ảnh.',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // --- DATA PREPARATION ---
            $categoryId = $request->input('type');
            $propertyType = ($request->input('transactionType') === 'sale') ? 0 : 1; // 0: Sale, 1: Rent

            // Construct Title & Address
            $streetId = $request->input('street');
            $wardId = $request->input('ward');
            $houseNumber = $request->input('houseNumber') ?? '';

            $streetName = '';
            if ($streetId) {
                $streetObj = LocationsStreet::where('code', $streetId)->first();
                if ($streetObj)
                    $streetName = $streetObj->street_name;
            }

            $wardName = '';
            if ($wardId) {
                $wardObj = LocationsWard::where('code', $wardId)->first();
                if ($wardObj)
                    $wardName = $wardObj->full_name;
            }

            // Format: "123 Đường A, Phường B - Đà Lạt, Tỉnh Lâm Đồng"
            $addressParts = [];
            if ($houseNumber)
                $addressParts[] = $houseNumber;
            if ($streetName)
                $addressParts[] = $streetName;
            if ($wardName)
                $addressParts[] = $wardName;
            $address = implode(', ', $addressParts) . ' - Đà Lạt, Tỉnh Lâm Đồng';

            // Generate Title: "Bán nhà/Cho thuê nhà [Category] [Street], [Ward] - Đà Lạt"
            $category = Category::find($categoryId);
            $catName = $category ? $category->category : 'Bất động sản';
            $actionName = ($propertyType == 0) ? 'Bán' : 'Cho thuê';

            $titleParts = [$actionName . ' ' . strtolower($catName)];
            if ($streetName)
                $titleParts[] = $streetName;
            if ($wardName)
                $titleParts[] = $wardName;
            $title = implode(', ', $titleParts) . ' - Đà Lạt';


            // --- 1. HOST (Contact) ---
            $contact = $request->input('contact');
            if (is_string($contact)) {
                $contact = json_decode($contact, true);
            }

            // Format phone number to international format (84...)
            $rawPhone = $contact['phone'] ?? $customer->phone ?? '';
            $phone = preg_replace('/[^0-9]/', '', $rawPhone); // Remove non-numeric chars
            if (substr($phone, 0, 1) === '0') {
                $phone = '84' . substr($phone, 1);
            }

            // Fix duplicate host: Check if host exists
            $host = CrmHost::firstOrNew(['contact' => $phone]);

            // Update name/gender if provided (or if new)
            $host->name = $contact['name'] ?? $customer->name ?? 'Unknown';
            // Only update gender if it's new or user explicitly provides it (and it's not empty)
            if (!empty($contact['gender'])) {
                $host->gender = $contact['gender'];
            }
            // Ensure contact is set for new records
            $host->contact = $phone;

            // Handle Note (stored in 'about' column)
            if (!empty($contact['note'])) {
                $newNote = trim($contact['note']);
                if (empty($host->about)) {
                    $host->about = $newNote;
                }
                else {
                    // Check if note already exists to avoid duplication
                    if (!Str::contains($host->about, $newNote)) {
                        $timestamp = Carbon::now()->format('d/m/Y H:i');
                        $host->about .= "\n[{$timestamp}]: {$newNote}";
                    }
                }
            }

            $host->save();


            // --- 2. PROPERTY ---
            $property = new Property();
            $property->category_id = $categoryId;
            $property->package_id = 1; // Default
            $property->title = $title;
            $property->description = $request->input('description');
            $property->address = $address;
            $property->client_address = $address;
            $property->property_type = $propertyType;
            $property->rentduration = ($propertyType == 1) ? $request->input('rentduration') : null;
            $property->price = $request->input('price');
            $property->added_by = $customer->id;
            $property->status = 0; // Pending approval
            $property->host_id = $host->id;
            $property->post_type = 1; // User submitted
            $property->street_code = $streetId;
            $property->ward_code = $wardId;

            // Commission
            $commissionRate = $request->input('commissionRate', 0);
            $property->commission = ($property->price * ($commissionRate / 100));

            // Slug
            $slug = Str::slug($title) . '-' . time();
            $property->slug = $slug;

            // Map Location
            if ($request->has('latitude'))
                $property->latitude = $request->input('latitude');
            if ($request->has('longitude'))
                $property->longitude = $request->input('longitude');

            $property->save();


            // --- 3. IMAGES ---
            // Title Image
            $imagePath = public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH');
            if (!is_dir($imagePath)) {
                mkdir($imagePath, 0777, true);
            }

            // Avatar (title_image)
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move($imagePath, $filename);

                $property->title_image = $filename;
                $property->save();
            }

            // 3D Image
            if ($request->hasFile('threeD_image')) {
                $threeDPath = public_path('images') . config('global.3D_IMG_PATH');
                if (!is_dir($threeDPath)) {
                    mkdir($threeDPath, 0777, true);
                }
                $file = $request->file('threeD_image');
                $filename = microtime(true) . "." . $file->getClientOriginalExtension();
                $file->move($threeDPath, $filename);
                $property->threeD_image = $filename;
                $property->save();
            }

            // Gallery & Legal Path
            $galleryPathBase = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH');
            if (!is_dir($galleryPathBase)) {
                mkdir($galleryPathBase, 0777, true);
            }
            $galleryPath = $galleryPathBase . "/" . $property->id;
            if (!is_dir($galleryPath)) {
                mkdir($galleryPath, 0777, true);
            }

            // Gallery (others)
            if ($request->hasFile('others')) {
                foreach ($request->file('others') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($galleryPath, $filename);

                    $propImg = new PropertyImages();
                    $propImg->propertys_id = $property->id;
                    $propImg->image = $filename;
                    $propImg->save();
                }
            }

            // Legal (legal_images)
            if ($request->hasFile('legal')) {
                foreach ($request->file('legal') as $file) {
                    $filename = time() . '_legal_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($galleryPath, $filename);

                    $legalImg = new PropertyLegalImage();
                    $legalImg->propertys_id = $property->id;
                    $legalImg->image = $filename;
                    $legalImg->save();
                }
            }


            // --- 4. PARAMETERS ---
            $parameters = $request->input('parameters');
            if (is_string($parameters))
                $parameters = json_decode($parameters, true);

            $destinationPathforparam = public_path('images') . config('global.PARAMETER_IMAGE_PATH');
            if (!is_dir($destinationPathforparam)) {
                mkdir($destinationPathforparam, 0777, true);
            }

            $excludedNames = ['Diện tích', 'Pháp lý', 'Giá m2'];

            if (is_array($parameters)) {
                foreach ($parameters as $paramId => $val) {
                    $paramDef = parameter::find($paramId);
                    if (!$paramDef || in_array($paramDef->name, $excludedNames))
                        continue;

                    $assignParam = new AssignParameters();
                    $assignParam->modal()->associate($property);
                    $assignParam->parameter_id = $paramId;

                    // Check for file upload for this parameter
                    if ($request->hasFile("parameters.$paramId")) {
                        $profile = $request->file("parameters.$paramId");
                        $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                        $profile->move($destinationPathforparam, $imageName);
                        $assignParam->value = $imageName;
                    }
                    else {
                        if (empty($val))
                            continue;
                        $assignParam->value = $val;
                    }
                    $assignParam->save();
                }
            }


            // --- 5. FACILITIES ---
            $amenities = $request->input('amenities');
            if (is_string($amenities))
                $amenities = json_decode($amenities, true);

            if (is_array($amenities)) {
                foreach ($amenities as $facId => $val) {
                    if (!empty($val)) {
                        $assignFac = new AssignedOutdoorFacilities();
                        $assignFac->property_id = $property->id;
                        $assignFac->facility_id = $facId;
                        $assignFac->distance = $val;
                        $assignFac->save();
                    }
                }
            }

            DB::commit();

            $this->notifyNewListingToTelegram($property, $customer);

            return response()->json([
                'success' => true,
                'redirect_url' => route('webapp.add_listing_success', ['slug' => $slug])
            ]);

        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addListingSuccess(Request $request)
    {
        $slug = $request->input('slug');
        return view('frontend_dashboard_add_listing_success', compact('slug'));
    }

    public function editListing(Request $request, $id)
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer) {
            return redirect()->route('webapp');
        }

        $property = Property::with(['host', 'parameters', 'assignfacilities', 'category'])
            ->where('id', $id)
            ->where('added_by', $customer->id)
            ->first();

        if (!$property) {
            return redirect()->route('webapp.listings')->with('error', 'Tin đăng không tồn tại hoặc bạn không có quyền chỉnh sửa.');
        }

        // Build editProperty data for the frontend
        $editData = [
            'id' => $property->id,
            'transactionType' => ($property->property_type == 0) ? 'sale' : 'rent',
            'type' => $property->category_id,
            'ward' => $property->ward_code,
            'street' => $property->street_code,
            'houseNumber' => $property->street_number ?? '',
            'price' => $property->price,
            'rentduration' => $property->rentduration ?? 'Monthly',
            'description' => $property->description ?? '',
            'latitude' => $property->latitude,
            'longitude' => $property->longitude,
            'commissionRate' => 2, // default
        ];

        // Calculate commission rate from stored commission
        if ($property->price > 0 && $property->commission > 0) {
            $editData['commissionRate'] = round(($property->commission / $property->price) * 100, 1);
        }

        // Contact info from host
        if ($property->host) {
            $phone = $property->host->contact ?? '';
            // Convert 84xxx to 0xxx for display
            if (substr($phone, 0, 2) === '84') {
                $phone = '0' . substr($phone, 2);
            }
            $editData['contact'] = [
                'gender' => $property->host->gender ?? '1',
                'name' => $property->host->name ?? '',
                'phone' => $phone,
                'note' => '',
            ];
        }

        // Parameters (key-value map)
        $editParams = [];
        foreach ($property->parameters as $param) {
            $editParams[$param->id] = $param->pivot->value;
        }
        $editData['parameters'] = $editParams;

        // Extract area from parameters
        $areaParamId = config('global.area');
        $editData['area'] = $editParams[$areaParamId] ?? '';

        // Extract legal from parameters
        $legalParamId = config('global.legal');
        $editData['legal'] = $editParams[$legalParamId] ?? '';

        // Facilities (key-value map: facility_id => distance)
        $editAmenities = [];
        foreach ($property->assignfacilities as $fac) {
            $editAmenities[$fac->facility_id] = $fac->distance ?? '';
        }
        $editData['amenities'] = $editAmenities;

        // Images
        $editData['titleImage'] = $property->title_image ?: null;
        $editData['gallery'] = $property->gallery->map(function ($img) {
            return [
            'id' => $img->id,
            'url' => $img->image_url,
            ];
        })->toArray();
        $editData['legalImages'] = $property->legalimages->map(function ($img) {
            return [
            'id' => $img->id,
            'url' => $img->image_url,
            ];
        })->toArray();

        $editProperty = json_encode($editData);

        // Load the same reference data as addListing
        $dbCategories = Category::where('status', '1')->orderBy('order', 'asc')->get();
        $propertyTypes = $dbCategories->map(function ($cat) {
            $isHouse = !Str::contains(Str::lower($cat->category), ['đất', 'land']);
            $parameterIds = [];
            if ($cat->parameter_types) {
                $parameterIds = array_map('intval', explode(',', $cat->parameter_types));
            }
            $icon = 'fa-house';
            $lowerName = Str::lower($cat->category);
            if (Str::contains($lowerName, 'biệt thự'))
                $icon = 'fa-hotel';
            elseif (Str::contains($lowerName, 'khách sạn'))
                $icon = 'fa-bell-concierge';
            elseif (Str::contains($lowerName, 'chung cư'))
                $icon = 'fa-building';
            elseif (Str::contains($lowerName, 'đất'))
                $icon = 'fa-map-location-dot';
            return [
            'id' => $cat->id,
            'name' => $cat->category,
            'icon' => $icon,
            'isHouse' => $isHouse,
            'parameter_ids' => $parameterIds
            ];
        });

        $districtCode = config('location.district_code');
        $wards = LocationsWard::select('code', 'full_name')
            ->where('district_code', $districtCode)
            ->orderByRaw("CASE
                            WHEN full_name LIKE 'phường%' THEN 1
                            WHEN full_name LIKE 'Xã%' THEN 2
                            ELSE 3 END,
                          CAST(SUBSTRING_INDEX(full_name, ' ', -1) AS UNSIGNED),
                          full_name")
            ->get()
            ->map(function ($w) {
            return ['id' => $w->code, 'name' => $w->full_name, 'icon' => 'fa-map-pin'];
        });

        $streets = LocationsStreet::select('code', 'street_name')
            ->where('district_code', $districtCode)
            ->get()
            ->map(function ($s) {
            return ['id' => $s->code, 'name' => $s->street_name];
        });

        $parameters = parameter::with('assigned_parameter')->get()->map(function ($param) {
            return [
            'id' => $param->id,
            'name' => $param->name,
            'type_of_parameter' => $param->type_of_parameter,
            'type_values' => $param->type_values
            ];
        });

        $assignParameters = AssignParameters::all()->map(function ($ap) {
            return [
            'id' => $ap->id,
            'property_id' => $ap->property_id,
            'parameter_id' => $ap->parameter_id,
            'value' => $ap->value
            ];
        });

        $facilities = OutdoorFacilities::all();

        $legalTypes = [
            ['value' => 'Sổ xây dựng', 'name' => 'Sổ xây dựng', 'icon' => 'fa-file-contract'],
            ['value' => 'Sổ nông nghiệp', 'name' => 'Sổ nông nghiệp', 'icon' => 'fa-file-contract'],
            ['value' => 'Sổ phân quyền xây dựng', 'name' => 'Sổ phân quyền xây dựng', 'icon' => 'fa-file-signature'],
            ['value' => 'Sổ phân quyền nông nghiệp', 'name' => 'Sổ phân quyền nông nghiệp', 'icon' => 'fa-file-signature'],
            ['value' => 'Giấy tay', 'name' => 'Giấy tay / Vi bằng', 'icon' => 'fa-file-alt']
        ];

        $directions = ['Đông', 'Tây', 'Nam', 'Bắc', 'Đông Nam', 'Đông Bắc', 'Tây Nam', 'Tây Bắc'];
        $commissionRates = [1, 1.5, 2, 2.5, 3];

        return view('frontend_dashboard_add_listing', compact(
            'propertyTypes', 'wards', 'streets', 'parameters', 'assignParameters',
            'facilities', 'legalTypes', 'directions', 'commissionRates', 'editProperty'
        ));
    }

    public function updateForm(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $customer = Auth::guard('webapp')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập lại.'], 401);
            }

            $property = Property::where('id', $id)->where('added_by', $customer->id)->first();
            if (!$property) {
                return response()->json(['success' => false, 'message' => 'Tin đăng không tồn tại hoặc bạn không có quyền chỉnh sửa.'], 404);
            }

            // Validation — avatar is optional when editing
            $validator = Validator::make($request->all(), [
                'type' => 'required',
                'transactionType' => 'required',
                'rentduration' => 'nullable|string',
                'ward' => 'required',
                'price' => 'required|numeric|min:0',
                'area' => 'required|numeric|min:0',
                'commissionRate' => 'required|numeric',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'avatar' => 'nullable|image|max:5120',
            ], [
                'type.required' => 'Vui lòng chọn loại bất động sản.',
                'transactionType.required' => 'Vui lòng chọn hình thức giao dịch.',
                'ward.required' => 'Vui lòng chọn khu vực (Phường/Xã).',
                'price.required' => 'Vui lòng nhập mức giá.',
                'area.required' => 'Vui lòng nhập diện tích.',
                'commissionRate.required' => 'Vui lòng chọn mức hoa hồng.',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // --- DATA PREPARATION ---
            $categoryId = $request->input('type');
            $propertyType = ($request->input('transactionType') === 'sale') ? 0 : 1;

            $streetId = $request->input('street');
            $wardId = $request->input('ward');
            $houseNumber = $request->input('houseNumber') ?? '';

            $streetName = '';
            if ($streetId) {
                $streetObj = LocationsStreet::where('code', $streetId)->first();
                if ($streetObj)
                    $streetName = $streetObj->street_name;
            }

            $wardName = '';
            if ($wardId) {
                $wardObj = LocationsWard::where('code', $wardId)->first();
                if ($wardObj)
                    $wardName = $wardObj->full_name;
            }

            $addressParts = [];
            if ($houseNumber)
                $addressParts[] = $houseNumber;
            if ($streetName)
                $addressParts[] = $streetName;
            if ($wardName)
                $addressParts[] = $wardName;
            $address = implode(', ', $addressParts) . ' - Đà Lạt, Tỉnh Lâm Đồng';

            $category = Category::find($categoryId);
            $catName = $category ? $category->category : 'Bất động sản';
            $actionName = ($propertyType == 0) ? 'Bán' : 'Cho thuê';

            $titleParts = [$actionName . ' ' . strtolower($catName)];
            if ($streetName)
                $titleParts[] = $streetName;
            if ($wardName)
                $titleParts[] = $wardName;
            $title = implode(', ', $titleParts) . ' - Đà Lạt';

            // --- 1. HOST (Contact) ---
            $contact = $request->input('contact');
            if (is_string($contact)) {
                $contact = json_decode($contact, true);
            }

            $rawPhone = $contact['phone'] ?? $customer->phone ?? '';
            $phone = preg_replace('/[^0-9]/', '', $rawPhone);
            if (substr($phone, 0, 1) === '0') {
                $phone = '84' . substr($phone, 1);
            }

            $host = CrmHost::firstOrNew(['contact' => $phone]);
            $host->name = $contact['name'] ?? $customer->name ?? 'Unknown';
            if (!empty($contact['gender'])) {
                $host->gender = $contact['gender'];
            }
            $host->contact = $phone;

            if (!empty($contact['note'])) {
                $newNote = trim($contact['note']);
                if (empty($host->about)) {
                    $host->about = $newNote;
                }
                else {
                    if (!Str::contains($host->about, $newNote)) {
                        $timestamp = Carbon::now()->format('d/m/Y H:i');
                        $host->about .= "\n[{$timestamp}]: {$newNote}";
                    }
                }
            }
            $host->save();

            // --- 2. UPDATE PROPERTY ---
            $property->category_id = $categoryId;
            $property->title = $title;
            $property->description = $request->input('description');
            $property->address = $address;
            $property->client_address = $address;
            $property->property_type = $propertyType;
            $property->rentduration = ($propertyType == 1) ? $request->input('rentduration') : null;
            $property->price = $request->input('price');
            $property->host_id = $host->id;
            $property->street_code = $streetId;
            $property->ward_code = $wardId;

            $commissionRate = $request->input('commissionRate', 0);
            $property->commission = ($property->price * ($commissionRate / 100));

            if ($request->has('latitude'))
                $property->latitude = $request->input('latitude');
            if ($request->has('longitude'))
                $property->longitude = $request->input('longitude');

            $property->save();

            // --- 3. IMAGES ---
            $imagePath = public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH');
            if (!is_dir($imagePath)) {
                mkdir($imagePath, 0777, true);
            }

            // Avatar (title_image) — only update if new file uploaded
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move($imagePath, $filename);
                $property->title_image = $filename;
                $property->save();
            }

            // Gallery & Legal Path
            $galleryPathBase = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH');
            if (!is_dir($galleryPathBase)) {
                mkdir($galleryPathBase, 0777, true);
            }
            $galleryPath = $galleryPathBase . "/" . $property->id;
            if (!is_dir($galleryPath)) {
                mkdir($galleryPath, 0777, true);
            }

            // Delete removed gallery images
            $keepGalleryIds = $request->input('keep_gallery_ids');
            if (is_string($keepGalleryIds)) {
                $keepGalleryIds = json_decode($keepGalleryIds, true);
            }
            if (is_array($keepGalleryIds)) {
                PropertyImages::where('propertys_id', $property->id)
                    ->whereNotIn('id', $keepGalleryIds)
                    ->delete();
            }

            // Delete removed legal images
            $keepLegalIds = $request->input('keep_legal_ids');
            if (is_string($keepLegalIds)) {
                $keepLegalIds = json_decode($keepLegalIds, true);
            }
            if (is_array($keepLegalIds)) {
                PropertyLegalImage::where('propertys_id', $property->id)
                    ->whereNotIn('id', $keepLegalIds)
                    ->delete();
            }

            // Add new gallery images
            if ($request->hasFile('others')) {
                foreach ($request->file('others') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($galleryPath, $filename);
                    $propImg = new PropertyImages();
                    $propImg->propertys_id = $property->id;
                    $propImg->image = $filename;
                    $propImg->save();
                }
            }

            // Add new legal images
            if ($request->hasFile('legal')) {
                foreach ($request->file('legal') as $file) {
                    $filename = time() . '_legal_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($galleryPath, $filename);
                    $legalImg = new PropertyLegalImage();
                    $legalImg->propertys_id = $property->id;
                    $legalImg->image = $filename;
                    $legalImg->save();
                }
            }

            // --- 4. PARAMETERS ---
            // Delete existing parameters and re-create
            AssignParameters::where('modal_id', $property->id)
                ->where('modal_type', 'App\\Models\\Property')
                ->delete();

            $parameters = $request->input('parameters');
            if (is_string($parameters))
                $parameters = json_decode($parameters, true);

            $destinationPathforparam = public_path('images') . config('global.PARAMETER_IMAGE_PATH');
            if (!is_dir($destinationPathforparam)) {
                mkdir($destinationPathforparam, 0777, true);
            }

            $excludedNames = ['Diện tích', 'Pháp lý', 'Giá m2'];

            if (is_array($parameters)) {
                foreach ($parameters as $paramId => $val) {
                    $paramDef = parameter::find($paramId);
                    if (!$paramDef || in_array($paramDef->name, $excludedNames))
                        continue;

                    $assignParam = new AssignParameters();
                    $assignParam->modal()->associate($property);
                    $assignParam->parameter_id = $paramId;

                    if ($request->hasFile("parameters.$paramId")) {
                        $profile = $request->file("parameters.$paramId");
                        $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                        $profile->move($destinationPathforparam, $imageName);
                        $assignParam->value = $imageName;
                    }
                    else {
                        if (empty($val))
                            continue;
                        $assignParam->value = $val;
                    }
                    $assignParam->save();
                }
            }

            // --- 5. FACILITIES ---
            // Delete existing facilities and re-create
            AssignedOutdoorFacilities::where('property_id', $property->id)->delete();

            $amenities = $request->input('amenities');
            if (is_string($amenities))
                $amenities = json_decode($amenities, true);

            if (is_array($amenities)) {
                foreach ($amenities as $facId => $val) {
                    if (!empty($val)) {
                        $assignFac = new AssignedOutdoorFacilities();
                        $assignFac->property_id = $property->id;
                        $assignFac->facility_id = $facId;
                        $assignFac->distance = $val;
                        $assignFac->save();
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật tin đăng thành công.',
                'redirect_url' => route('webapp.listings')
            ]);

        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkHostPhone(Request $request)
    {
        $phone = $request->input('phone');
        if (!$phone)
            return response()->json([]);

        // Normalize phone for searching
        $searchPhone = preg_replace('/[^0-9]/', '', $phone);

        // Try both formats: with 0 and with 84
        $phones = [];
        if (substr($searchPhone, 0, 1) === '0') {
            $phones[] = '84' . substr($searchPhone, 1);
            $phones[] = $searchPhone;
        }
        elseif (substr($searchPhone, 0, 2) === '84') {
            $phones[] = $searchPhone;
            $phones[] = '0' . substr($searchPhone, 2);
        }
        else {
            $phones[] = $searchPhone;
        }

        $hosts = CrmHost::whereIn('contact', $phones)
            ->select('id', 'name', 'contact', 'gender')
            ->get();

        return response()->json($hosts);
    }

    public function addCustomer(Request $request)
    {
        // Get customer data for authenticating (optional if required by view)
        $customer = Auth::guard('webapp')->user();

        // 1. Property Types (Categories)
        $dbCategories = Category::where('status', '1')->orderBy('order', 'asc')->get();
        $propertyTypes = $dbCategories->map(function ($cat) {
            $isHouse = !Str::contains(Str::lower($cat->category), ['đất', 'land']);
            $parameterIds = [];
            if ($cat->parameter_types) {
                $parameterIds = array_map('intval', explode(',', $cat->parameter_types));
            }
            $icon = 'fa-house';
            $lowerName = Str::lower($cat->category);
            if (Str::contains($lowerName, 'biệt thự'))
                $icon = 'fa-hotel';
            elseif (Str::contains($lowerName, 'khách sạn'))
                $icon = 'fa-bell-concierge';
            elseif (Str::contains($lowerName, 'chung cư'))
                $icon = 'fa-building';
            elseif (Str::contains($lowerName, 'đất'))
                $icon = 'fa-map-location-dot';

            return [
            'id' => $cat->id,
            'name' => $cat->category,
            'icon' => $icon,
            'isHouse' => $isHouse,
            'parameter_ids' => $parameterIds
            ];
        });

        // 2. Wards
        $districtCode = config('location.district_code');
        $wards = LocationsWard::select('code', 'full_name')
            ->where('district_code', $districtCode)
            ->orderByRaw("CASE
                            WHEN full_name LIKE 'phường%' THEN 1
                            WHEN full_name LIKE 'Xã%' THEN 2
                            ELSE 3 END,
                          CAST(SUBSTRING_INDEX(full_name, ' ', -1) AS UNSIGNED),
                          full_name")
            ->get()
            ->map(function ($w) {
            return [
            'id' => $w->code,
            'name' => $w->full_name,
            'icon' => 'fa-map-pin'
            ];
        });

        // 3. Streets
        $streets = LocationsStreet::select('code', 'street_name')
            ->where('district_code', $districtCode)
            ->get()
            ->map(function ($s) {
            return [
            'id' => $s->code,
            'name' => $s->street_name
            ];
        });

        return view('frontend_dashboard_add_customer', compact('propertyTypes', 'wards', 'streets'));
    }

    public function storeCustomer(Request $request)
    {
        try {
            DB::beginTransaction();

            $customer = Auth::guard('webapp')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập lại.'], 401);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'lead_type' => 'required|in:buy,rent',
                'categories' => 'nullable|array',
                'wards' => 'nullable|array',
                'price_min' => 'nullable|numeric|min:0',
                'price_max' => 'nullable|numeric|min:0',
                'purpose' => 'nullable|string',
            ], [
                'name.required' => 'Vui lòng nhập tên khách hàng.',
                'phone.required' => 'Vui lòng nhập số điện thoại.',
                'lead_type.required' => 'Vui lòng chọn nhu cầu (Mua/Thuê).',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // 1. Create or Update CrmCustomer
            // Normalize phone
            $rawPhone = $request->input('phone');
            $phone = preg_replace('/[^0-9]/', '', $rawPhone);
            if (substr($phone, 0, 1) === '0') {
                $phone = '84' . substr($phone, 1);
            }

            $crmCustomer = CrmCustomer::firstOrNew(['contact' => $phone]);
            $crmCustomer->full_name = $request->input('name');
            $crmCustomer->contact = $phone;
            $crmCustomer->save();

            // 2. Create CrmLead
            $lead = new CrmLead();
            $lead->user_id = $customer->id; // The broker who added this customer
            $lead->customer_id = $crmCustomer->id;
            $lead->lead_type = $request->input('lead_type');
            $lead->categories = $request->input('categories');
            $lead->wards = $request->input('wards');
            $lead->demand_rate_min = $request->input('price_min', 0);
            $lead->demand_rate_max = $request->input('price_max', 0);
            $lead->purpose = $request->input('purpose');
            $lead->source_note = 'telegram_webapp';

            $note = $request->input('purpose', '');
            if ($request->filled('street')) {
                $streetCode = $request->input('street');
                $streetObj = LocationsStreet::where('code', $streetCode)->first();
                if ($streetObj) {
                    if ($note)
                        $note .= ' - ';
                    $note .= 'Tên đường: ' . $streetObj->street_name;
                }
            }
            $lead->note = $note;

            $lead->status = 'new';
            $lead->save();

            DB::commit();

            $this->notifyNewLeadToTelegram($lead, $crmCustomer, $customer);

            return response()->json([
                'success' => true,
                'message' => 'Thêm khách hàng thành công.',
                'redirect_url' => route('webapp.leads') // Or back to dashboard
            ]);

        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    private function notifyNewListingToTelegram(Property $property, $customer): void
    {
        try {
            $notificationService = app(NotificationService::class);
            $type = $property->property_type == 0 ? 'Bán' : 'Cho thuê';
            $price = number_format((float)$property->price, 0, ',', '.');
            $posterName = $customer->name ?? 'N/A';
            $posterPhone = $customer->mobile ?? $customer->phone ?? 'N/A';
            $propertyUrl = route('property.showid', ['id' => $property->id]);

            $message = "🏠 *BĐS MỚI TỪ WEBAPP*\n";
            $message .= "----------------\n";
            $message .= "🆔 ID: `{$property->id}`\n";
            $message .= "📌 Loại tin: {$type}\n";
            $message .= "📝 Tiêu đề: " . $this->escapeTelegramText($property->title) . "\n";
            $message .= "📍 Địa chỉ: " . $this->escapeTelegramText($property->address) . "\n";
            $message .= "💰 Giá: {$price} VNĐ\n";
            $message .= "👤 Người đăng: " . $this->escapeTelegramText($posterName) . "\n";
            $message .= "📞 Liên hệ: " . $this->escapeTelegramText($posterPhone) . "\n";
            $message .= "📊 Trạng thái: Chờ duyệt\n";
            $message .= "🔗 [Xem tin]({$propertyUrl})";

            $notificationService->sendToGroup('public_channel', $message);

            // In-app notification to bds_admin + admin
            $admins = Customer::whereIn('role', ['bds_admin', 'admin'])->get();
            app(InAppNotificationService::class)->notifyMany($admins, 'property_pending', 'admin', 'status', [
                'title' => 'BĐS chờ duyệt: ' . ($property->title ?? 'BĐS'),
                'body'  => 'Broker ' . ($customer->name ?? 'N/A') . ' gửi lúc ' . now()->format('H:i') . ' — ' . $type . ', ' . $price . ' VNĐ',
                'notifiable_type' => Property::class,
                'notifiable_id'   => $property->id,
                'actor_id'        => $customer->id,
                'data'  => [
                    'property_id'  => $property->id,
                    'title'        => $property->title,
                    'broker_name'  => $customer->name ?? '',
                    'price'        => $price,
                ],
            ]);
        }
        catch (\Exception $e) {
            Log::warning('Failed to send listing telegram notification: ' . $e->getMessage());
        }
    }

    private function notifyNewLeadToTelegram(CrmLead $lead, CrmCustomer $crmCustomer, $creator): void
    {
        try {
            $notificationService = app(NotificationService::class);
            $leadType = $lead->lead_type === 'rent' ? 'Cần thuê' : 'Cần mua';
            $budgetMin = number_format((float)($lead->demand_rate_min ?? 0), 0, ',', '.');
            $budgetMax = number_format((float)($lead->demand_rate_max ?? 0), 0, ',', '.');
            $wards = 'Không giới hạn';
            if (is_array($lead->wards) && count($lead->wards) > 0) {
                $wardNames = LocationsWard::whereIn('code', $lead->wards)->pluck('full_name')->toArray();
                $wards = count($wardNames) > 0 ? implode(', ', $wardNames) : implode(', ', $lead->wards);
            }

            $categories = 'Không giới hạn';
            if (is_array($lead->categories) && count($lead->categories) > 0) {
                $categoryNames = Category::whereIn('id', $lead->categories)->pluck('category')->toArray();
                $categories = count($categoryNames) > 0 ? implode(', ', $categoryNames) : implode(', ', $lead->categories);
            }
            $creatorName = $creator->name ?? 'N/A';
            $creatorPhone = $creator->mobile ?? $creator->phone ?? 'N/A';
            $leadUrl = route('webapp.leads');

            $message = "🎯 [ĐÀ LẠT BĐS] - KHÁCH HÀNG MỚI\n";
            $message .= "----------------\n";
            $message .= "🆔 Lead ID: `{$lead->id}`\n";
            $message .= "👤 Khách hàng: " . $this->escapeTelegramText($crmCustomer->full_name ?? 'N/A') . "\n";
            //$message .= "📞 SĐT khách: " . $this->escapeTelegramText($crmCustomer->contact ?? 'N/A') . "\n";
            $message .= "🏷️ Nhu cầu: {$leadType}\n";
            $message .= "💰 Ngân sách: {$budgetMin} - {$budgetMax} VNĐ\n";
            $message .= "📍 Khu vực: " . $this->escapeTelegramText($wards) . "\n";
            $message .= "🏠 Loại BĐS: " . $this->escapeTelegramText($categories) . "\n";
            $message .= "🧭 Mục đích: " . $this->escapeTelegramText($lead->purpose ?? 'N/A') . "\n";
            //$message .= "👨‍💼 Người tạo: " . $this->escapeTelegramText($creatorName) . " - " . $this->escapeTelegramText($creatorPhone) . "\n";
            //$message .= "🔗 [Mở danh sách lead]({$leadUrl})";

            $notificationService->sendToGroup('public_channel', $message);

            // Gửi vào group sale_admin với 1 nút web_app để mở trang phân công
            $assignUrl = URL::temporarySignedRoute(
                'webapp.leads.assign-page',
                Carbon::now()->addHours(24),
                ['id' => $lead->id]
            );

            ['text' => $groupMessage, 'keyboard' => $keyboard] =
                TelegramMessageTemplates::newLeadForGroupWebApp($lead->load(['customer', 'user']), $assignUrl);

            $notificationService->sendWithInlineKeyboard(
                (string) config('services.telegram.groups.sale_admin'),
                $groupMessage,
                $keyboard
            );

            // Gửi xác nhận đến broker tạo lead
            if ($creator && $creator->telegram_id) {
                $brokerMsg = "✅ *Đà Lạt BĐS đã tiếp nhận thông tin khách hàng của bạn!*\n";
                $brokerMsg .= "Đội ngũ chúng tôi sẽ hỗ trợ tư vấn và kết nối sớm nhất có thể.\n";
                $brokerMsg .= "----------------\n";
                $brokerMsg .= "🆔 Lead ID: `{$lead->id}`\n";
                $brokerMsg .= "👤 Khách hàng: " . $this->escapeTelegramText($crmCustomer->full_name ?? 'N/A') . "\n";
                $brokerMsg .= "🏷️ Nhu cầu: {$leadType}\n";
                $brokerMsg .= "💰 Ngân sách: {$budgetMin} - {$budgetMax} VNĐ\n";
                $brokerMsg .= "📍 Khu vực: " . $this->escapeTelegramText($wards) . "\n";
                $brokerMsg .= "🏠 Loại BĐS: " . $this->escapeTelegramText($categories) . "\n";
                $notificationService->sendToCustomer($creator, $brokerMsg);
            }

            // In-app: notify sale_admin about new lead to assign
            $inAppService = app(InAppNotificationService::class);
            $leadBody = ($crmCustomer->full_name ?? 'N/A') . ' — ' . $leadType . ' | ' . $categories . ' | ' . $wards;
            $saleAdmins = Customer::whereIn('role', ['sale_admin', 'admin'])->get();
            $inAppService->notifyMany($saleAdmins, 'lead_created', 'lead', 'assigned', [
                'title' => 'Lead mới cần phân công',
                'body'  => $leadBody,
                'notifiable_type' => CrmLead::class,
                'notifiable_id'   => $lead->id,
                'actor_id'        => $creator->id ?? null,
                'data'  => [
                    'lead_id'       => $lead->id,
                    'customer_name' => $crmCustomer->full_name ?? '',
                    'lead_type'     => $leadType,
                    'budget'        => $budgetMin . ' - ' . $budgetMax,
                ],
            ]);
        }
        catch (\Exception $e) {
            Log::warning('Failed to send lead telegram notification: ' . $e->getMessage());
        }
    }

    /**
     * POST /webapp/log-action
     * Fire-and-forget action logging từ frontend webapp.
     */
    public function logAction(Request $request)
    {
        $customer = Auth::guard('webapp')->user();

        $validated = $request->validate([
            'subject_type'  => 'required|in:property,lead,deal',
            'subject_id'    => 'required|integer|min:1',
            'subject_title' => 'nullable|string|max:255',
            'action'        => 'required|in:call,share,edit,view,create,delete',
            'metadata'      => 'nullable|array',
        ]);

        \App\Models\WebappActionLog::create([
            ...$validated,
            'actor_id' => $customer?->id,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * GET /webapp/action-logs
     * Trả về danh sách logs cho admin/sale_admin/bds_admin xem.
     */
    public function actionLogs(Request $request)
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer || !in_array($customer->getEffectiveRole(), ['admin', 'sale_admin', 'bds_admin'])) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $query = \App\Models\WebappActionLog::with('actor')
            ->orderBy('created_at', 'desc');

        if ($request->subject_type) {
            $query->where('subject_type', $request->subject_type);
        }
        if ($request->action) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(20);

        // Counts by action, respecting subject_type filter only (not action filter)
        $countsQuery = \App\Models\WebappActionLog::query();
        if ($request->subject_type) {
            $countsQuery->where('subject_type', $request->subject_type);
        }
        $countsByAction = $countsQuery->selectRaw('action, count(*) as cnt')
            ->groupBy('action')
            ->pluck('cnt', 'action');

        return response()->json([
            'data'      => $logs->map(fn($log) => [
                'id'            => $log->id,
                'subject_type'  => $log->subject_type,
                'subject_label' => $log->getSubjectLabel(),
                'subject_id'    => $log->subject_id,
                'subject_title' => $log->subject_title,
                'action'        => $log->action,
                'action_label'  => $log->getActionLabel(),
                'action_color'  => $log->getActionColor(),
                'actor_name'    => $log->actor?->name ?? 'Hệ thống',
                'actor_initials'=> $log->actor ? mb_strtoupper(mb_substr($log->actor->name ?? 'S', 0, 1)) : 'S',
                'time_diff'     => $log->created_at->diffForHumans(),
                'time_full'     => $log->created_at->format('H:i d/m/Y'),
            ]),
            'has_more'        => $logs->hasMorePages(),
            'next_page'       => $logs->currentPage() + 1,
            'total'           => $logs->total(),
            'counts_by_action'=> $countsByAction,
        ]);
    }

    public function myCommissionsApi(Request $request)
    {
        try {
            $customer = Auth::guard('webapp')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $statusFilter = trim($request->input('status', ''));

            // Base query: commissions linked to deals where the lead belongs to this customer
            $baseQuery = CrmDealCommission::with(['deal.customer', 'property'])
                ->whereHas('deal.lead', function ($q) use ($customer) {
                    $q->where('sale_id', $customer->id)
                      ->orWhere('user_id', $customer->id);
                })
                ->where('status', '!=', CommissionStatus::CANCELLED->value);

            // Summary totals (computed before status filter)
            $received = (float)(clone $baseQuery)->where('status', CommissionStatus::COMPLETED->value)->sum('sale_commission');
            $pending  = (float)(clone $baseQuery)->whereIn('status', [CommissionStatus::DEPOSITED->value, CommissionStatus::NOTARIZING->value])->sum('sale_commission');
            $upcoming = (float)(clone $baseQuery)->where('status', CommissionStatus::PENDING_DEPOSIT->value)->sum('sale_commission');
            $total    = $received + $pending + $upcoming;

            // Monthly chart: last 6 months
            $chart    = [];
            $maxTrieu = 1;
            for ($i = 5; $i >= 0; $i--) {
                $month    = Carbon::now()->subMonths($i);
                $monthSum = (float)CrmDealCommission::whereHas('deal.lead', function ($q) use ($customer) {
                        $q->where('sale_id', $customer->id)->orWhere('user_id', $customer->id);
                    })
                    ->where('status', '!=', CommissionStatus::CANCELLED->value)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('sale_commission');
                $trieu = (int)round($monthSum / 1_000_000);
                if ($trieu > $maxTrieu) {
                    $maxTrieu = $trieu;
                }
                $chart[] = [
                    'label'      => 'T' . $month->month,
                    'trieu'      => $trieu,
                    'is_current' => $i === 0,
                ];
            }
            foreach ($chart as &$bar) {
                $bar['height_pct'] = max(5, (int)round($bar['trieu'] / $maxTrieu * 90));
            }
            unset($bar);

            // Apply status filter
            if ($statusFilter) {
                $baseQuery->where('status', $statusFilter);
            }

            $commissions = $baseQuery->orderBy('updated_at', 'desc')->get();

            $data = $commissions->map(function ($comm) {
                $deal     = $comm->deal;
                $cust     = $deal ? $deal->customer : null;
                $property = $comm->property;

                $saleRaw = (float)$comm->getRawOriginal('sale_commission');
                $appRaw  = (float)$comm->getRawOriginal('app_commission');
                $dealRaw = $deal ? (float)$deal->getRawOriginal('amount') : 0;

                // Commission percentage from property
                $commPct = 0;
                if ($property) {
                    $propPrice = (float)$property->getRawOriginal('price');
                    $propComm  = (float)$property->getRawOriginal('commission');
                    if ($propPrice > 0 && $propComm > 0) {
                        $commPct = round($propComm / $propPrice * 100, 1);
                    }
                }

                $statusVal = $comm->status instanceof CommissionStatus
                    ? $comm->status->value
                    : (string)$comm->status;
                $statusLabel = $comm->status instanceof CommissionStatus
                    ? $comm->status->label()
                    : $statusVal;

                return [
                    'id'                   => $comm->id,
                    'deal_id'              => $comm->deal_id,
                    'status'               => $statusVal,
                    'status_label'         => $statusLabel,
                    'property_name'        => $property ? $property->title : 'BĐS không xác định',
                    'customer_name'        => $cust ? $cust->full_name : 'Khách vãng lai',
                    'customer_phone'       => $cust ? ($cust->contact ?? '') : '',
                    'deal_amount_fmt'      => format_vnd($dealRaw),
                    'sale_commission_fmt'  => format_vnd($saleRaw),
                    'sale_commission_trieu'=> (int)round($saleRaw / 1_000_000),
                    'app_commission_fmt'   => format_vnd($appRaw),
                    'comm_pct'             => $commPct,
                    'notes'                => $comm->notes ?? '',
                    'created_at'           => $comm->created_at->format('d/m/Y'),
                    'updated_at'           => $comm->updated_at->format('d/m/Y'),
                ];
            });

            return response()->json([
                'success' => true,
                'summary' => [
                    'total_fmt'       => format_vnd($total),
                    'received_trieu'  => (int)round($received / 1_000_000),
                    'pending_trieu'   => (int)round($pending / 1_000_000),
                    'upcoming_trieu'  => (int)round($upcoming / 1_000_000),
                ],
                'chart'       => $chart,
                'commissions' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('myCommissionsApi: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống.'], 500);
        }
    }

    public function submitSupportTicket(Request $request)
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'subject'  => ['required', 'string', 'max:255'],
            'message'  => ['required', 'string', 'max:2000'],
        ], [
            'category.required' => 'Vui lòng chọn loại vấn đề.',
            'subject.required'  => 'Tiêu đề không được để trống.',
            'message.required'  => 'Mô tả vấn đề không được để trống.',
        ]);

        try {
            $notificationService = app(NotificationService::class);
            $msg  = "🎫 *YÊU CẦU HỖ TRỢ MỚI*\n";
            $msg .= "👤 " . $this->escapeTelegramText($customer->name ?? 'N/A') . " (ID: {$customer->id})\n";
            $msg .= "📞 " . $this->escapeTelegramText($customer->mobile ?? 'N/A') . "\n";
            $msg .= "🆔 Telegram: `" . ($customer->telegram_id ?? 'N/A') . "`\n";
            $msg .= "📂 Loại: " . $this->escapeTelegramText($validated['category']) . "\n";
            $msg .= "📝 Tiêu đề: " . $this->escapeTelegramText($validated['subject']) . "\n";
            $msg .= "💬 Nội dung:\n" . $this->escapeTelegramText($validated['message']);

            $notificationService->sendToGroup('bds_admin', $msg);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Support ticket error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi gửi yêu cầu.'], 500);
        }
    }

    // ========== NOTIFICATION SETTINGS ==========

    public function getNotifSettings(Request $request): JsonResponse
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer) {
            return response()->json(['success' => false], 401);
        }

        return response()->json([
            'success'  => true,
            'settings' => $customer->getMergedNotifSettings(),
        ]);
    }

    public function saveNotifSettings(Request $request): JsonResponse
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $validated = $request->validate([
            'master'                    => ['required', 'boolean'],
            'lead.assigned'             => ['required', 'boolean'],
            'lead.followup'             => ['required', 'boolean'],
            'lead.channels'             => ['required', 'array'],
            'lead.channels.*'           => ['string', 'in:telegram,in_app,zalo'],
            'deal.status'               => ['required', 'boolean'],
            'deal.feedback'             => ['required', 'boolean'],
            'deal.stuck'                => ['required', 'boolean'],
            'deal.channels'             => ['required', 'array'],
            'deal.channels.*'           => ['string', 'in:telegram,in_app,zalo'],
            'booking.day_before'        => ['required', 'boolean'],
            'booking.hour_before'       => ['required', 'boolean'],
            'booking.result'            => ['required', 'boolean'],
            'booking.channels'          => ['required', 'array'],
            'booking.channels.*'        => ['string', 'in:telegram,in_app,zalo'],
            'commission.approved'       => ['required', 'boolean'],
            'commission.status'         => ['required', 'boolean'],
            'commission.channels'       => ['required', 'array'],
            'commission.channels.*'     => ['string', 'in:telegram,in_app,zalo'],
            'property.status'           => ['required', 'boolean'],
            'property.interest'         => ['required', 'boolean'],
            'property.expiry'           => ['required', 'boolean'],
            'property.channels'         => ['required', 'array'],
            'property.channels.*'       => ['string', 'in:telegram,in_app,zalo'],
            'market.news'               => ['required', 'boolean'],
            'market.ai_suggest'         => ['required', 'boolean'],
            'market.promotions'         => ['required', 'boolean'],
            'quiet_hours.enabled'       => ['required', 'boolean'],
            'quiet_hours.start'         => ['required', 'string', 'regex:/^\d{2}:\d{2}$/'],
            'quiet_hours.end'           => ['required', 'string', 'regex:/^\d{2}:\d{2}$/'],
        ]);

        $customer->update(['notification_settings' => $validated]);

        return response()->json(['success' => true]);
    }

    private function escapeTelegramText(?string $text): string
    {
        if (!$text) {
            return '';
        }

        return str_replace(['*', '_', '`', '['], ['\*', '\_', '\`', '\['], $text);
    }

    // ========== ADMIN USER MANAGEMENT ==========

    private function adminUserInitials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name));
        if (count($parts) >= 2) {
            return mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr(end($parts), 0, 1));
        }
        return mb_strtoupper(mb_substr($name, 0, 2));
    }

    private function adminUserAvatarColor(int $id): string
    {
        $colors = ['#ef4444', '#f97316', '#eab308', '#22c55e', '#14b8a6', '#3b82f6', '#8b5cf6', '#ec4899'];
        return $colors[$id % count($colors)];
    }

    public function adminUsersApi(Request $request)
    {
        $tab    = $request->input('tab', 'pending');
        $search = trim($request->input('search', ''));

        $approvedRoles = ['broker', 'bds_admin', 'sale', 'sale_admin', 'admin'];

        // Build query per tab
        $query = Customer::query();
        if ($tab === 'pending') {
            $query->where('isActive', 1)->whereNotIn('role', $approvedRoles);
        } elseif ($tab === 'broker') {
            $query->where('isActive', 1)->whereIn('role', ['broker', 'bds_admin']);
        } elseif ($tab === 'sale') {
            $query->where('isActive', 1)->whereIn('role', ['sale', 'sale_admin']);
        } elseif ($tab === 'locked') {
            $query->where('isActive', 0);
        }

        if ($search !== '') {
            // Chuẩn hoá SĐT: "0947..." → tìm cả "84947..." và ngược lại
            $mobileVariant = null;
            if (preg_match('/^0(\d+)$/', $search, $m)) {
                $mobileVariant = '84' . $m[1];
            } elseif (preg_match('/^84(\d+)$/', $search, $m)) {
                $mobileVariant = '0' . $m[1];
            }

            $query->where(function ($q) use ($search, $mobileVariant) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('mobile', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
                if ($mobileVariant) {
                    $q->orWhere('mobile', 'like', "%$mobileVariant%");
                }
            });
        }

        $users = $query->orderByDesc('created_at')->get();

        $mappedUsers = $users->map(function (Customer $c) {
            $propCount = Property::where('added_by', $c->id)->count();
            return [
                'id'               => $c->id,
                'name'             => $c->name,
                'mobile'           => $c->mobile ?? '',
                'email'            => $c->email ?? '',
                'role'             => $c->role ?? 'customer',
                'isActive'         => (int) $c->isActive,
                'initials'         => $this->adminUserInitials($c->name),
                'avatar_color'     => $this->adminUserAvatarColor($c->id),
                'created_at_human' => $c->created_at ? $c->created_at->diffForHumans() : '',
                'property_count'   => $propCount,
            ];
        });

        // Stats counts
        $stats = [
            'active'  => Customer::where('isActive', 1)->count(),
            'pending' => Customer::where('isActive', 1)->whereNotIn('role', $approvedRoles)->count(),
            'broker'  => Customer::where('isActive', 1)->whereIn('role', ['broker', 'bds_admin'])->count(),
            'sale'    => Customer::where('isActive', 1)->whereIn('role', ['sale', 'sale_admin'])->count(),
            'locked'  => Customer::where('isActive', 0)->count(),
        ];

        return response()->json(['stats' => $stats, 'users' => $mappedUsers]);
    }

    public function adminApproveUser(int $id)
    {
        $target = Customer::findOrFail($id);
        $target->update(['role' => 'broker', 'isActive' => 1]);
        return response()->json(['success' => true]);
    }

    public function adminRejectUser(int $id)
    {
        $target = Customer::findOrFail($id);
        $target->update(['isActive' => 0]);
        return response()->json(['success' => true]);
    }

    public function adminApproveTempUser(int $id)
    {
        $target = Customer::findOrFail($id);
        $target->update(['role' => 'broker', 'isActive' => 1]);
        return response()->json(['success' => true]);
    }

    public function adminChangeUserRole(Request $request, int $id)
    {
        $me = Auth::guard('webapp')->user();
        if ($me->id === $id) {
            return response()->json(['success' => false, 'message' => 'Không thể đổi role của chính mình.'], 422);
        }

        $role = $request->input('role');
        if (!in_array($role, Customer::VALID_ROLES)) {
            return response()->json(['success' => false, 'message' => 'Role không hợp lệ.'], 422);
        }

        Customer::where('id', $id)->update(['role' => $role]);
        return response()->json(['success' => true]);
    }

    public function adminToggleUserActive(int $id)
    {
        $me = Auth::guard('webapp')->user();
        if ($me->id === $id) {
            return response()->json(['success' => false, 'message' => 'Không thể khoá chính mình.'], 422);
        }

        $target = Customer::findOrFail($id);
        $newStatus = $target->isActive ? 0 : 1;
        $target->update(['isActive' => $newStatus]);
        return response()->json(['success' => true, 'isActive' => $newStatus]);
    }

    public function adminDeleteUser(int $id)
    {
        $me = Auth::guard('webapp')->user();
        if ($me->id === $id) {
            return response()->json(['success' => false, 'message' => 'Không thể xoá chính mình.'], 422);
        }

        $target = Customer::findOrFail($id);
        if ($target->role === 'admin') {
            return response()->json(['success' => false, 'message' => 'Không thể xoá tài khoản admin.'], 422);
        }

        $target->delete();
        return response()->json(['success' => true]);
    }

    // ─── Property Approval API ───────────────────────────────────────────────

    public function adminPropertiesApi(Request $request): JsonResponse
    {
        $tab = $request->input('tab', 'pending');

        // Stats (always across all properties)
        $pendingCount      = Property::where('status', 0)->count();
        $approvedTodayCount = Property::where('status', 1)->whereDate('updated_at', today())->count();
        $totalApproved     = Property::where('status', 1)->count();

        $avgSeconds = Property::where('status', 1)
            ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, updated_at)) as avg_sec')
            ->value('avg_sec');
        $avgHours = $avgSeconds ? round($avgSeconds / 3600, 1) : null;

        $stats = [
            'pending'        => $pendingCount,
            'approved_today' => $approvedTodayCount,
            'total_approved' => $totalApproved,
            'avg_hours'      => $avgHours,
        ];

        // Build query per tab
        $query = Property::with(['category', 'ward', 'street', 'agent']);

        if ($tab === 'approved_today') {
            $query->where('status', 1)->whereDate('updated_at', today())->orderByDesc('updated_at');
        } elseif ($tab === 'rejected') {
            $query->where('status', 2)->orderByDesc('updated_at');
        } else {
            // pending (default)
            $query->where('status', 0)->orderBy('created_at', 'asc');
        }

        $properties = $query->get();

        $mapped = $properties->map(function (Property $p) {
            $ward   = optional($p->ward)->name ?? '';
            $street = optional($p->street)->street_name ?? '';

            $legalImages   = $p->legalimages ?? [];
            $galleryImages = $p->gallery ?? [];

            $checks = [
                'has_legal_docs'    => count($legalImages) > 0,
                'has_enough_photos' => count($galleryImages) >= 3,
                'location_valid'    => !empty($ward) && !empty($street),
                'price_reasonable'  => !empty($p->price),
            ];

            $broker = $p->agent;
            $brokerName = $broker?->name ?? 'Môi giới';
            $words = preg_split('/\s+/', trim($brokerName));
            $initials = mb_strtoupper(
                mb_substr($words[0], 0, 1)
                . (count($words) > 1 ? mb_substr(end($words), 0, 1) : '')
            );

            $createdAt = Carbon::parse($p->getRawOriginal('created_at'));

            return [
                'id'               => $p->id,
                'title'            => $p->title ?? '',
                'price'            => $p->price ?? '',
                'area'             => $p->area ? $p->area . ' m²' : null,
                'category_name'    => $p->category?->category ?? 'BĐS',
                'property_type'    => $p->property_type,
                'ward'             => $ward,
                'street'           => $street,
                'title_image'      => $p->title_image ?: null,
                'created_at_diff'  => $createdAt->diffForHumans(),
                'created_at_fmt'   => $createdAt->format('d/m/Y H:i'),
                'status'           => (int) $p->status,
                'direction'        => $p->direction,
                'number_room'      => $p->number_room,
                'legal'            => $p->legal,
                'broker_name'      => $brokerName,
                'broker_initials'  => $initials ?: 'BK',
                'broker_id'        => $broker?->id,
                'checks'           => $checks,
                'all_checks_pass'  => !in_array(false, $checks, true),
                'rejection_reason' => $p->rejection_reason,
                'rejection_note'   => $p->rejection_note,
            ];
        });

        return response()->json([
            'success'    => true,
            'stats'      => $stats,
            'properties' => $mapped,
            'tab'        => $tab,
        ]);
    }

    public function adminApproveProperty(int $id): JsonResponse
    {
        try {
            $property = Property::with('agent')->findOrFail($id);

            if ($property->status !== 0) {
                return response()->json(['success' => false, 'message' => 'BĐS này không ở trạng thái chờ duyệt.'], 422);
            }

            $property->update([
                'status'           => 1,
                'rejection_reason' => null,
                'rejection_note'   => null,
            ]);

            $broker = $property->agent;
            if ($broker && $broker->telegram_id) {
                $title   = $property->title ?? 'BĐS';
                $message = "✅ *TIN BĐS ĐÃ ĐƯỢC DUYỆT*\n"
                    . "────────────────\n"
                    . "🏠 {$title}\n"
                    . "🎉 Tin của bạn đã được đăng lên hệ thống!";
                app(NotificationService::class)->sendToCustomer($broker, $message);
            }

            // In-app notification to broker
            if ($broker) {
                app(InAppNotificationService::class)->notify($broker, 'property_approved', 'property', 'status', [
                    'title' => 'BĐS của bạn đã được duyệt!',
                    'body'  => ($property->title ?? 'BĐS') . ' — đang hiển thị công khai',
                    'notifiable_type' => Property::class,
                    'notifiable_id'   => $property->id,
                    'actor_id'        => Auth::guard('webapp')->id(),
                    'data'  => ['property_id' => $property->id, 'title' => $property->title],
                ]);
            }

            return response()->json([
                'success'       => true,
                'pending_count' => Property::where('status', 0)->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('adminApproveProperty error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống.'], 500);
        }
    }

    public function adminRejectProperty(Request $request, int $id): JsonResponse
    {
        try {
            $property = Property::with('agent')->findOrFail($id);

            if (!in_array($property->status, [0, 2])) {
                return response()->json(['success' => false, 'message' => 'Không thể từ chối BĐS đã duyệt.'], 422);
            }

            $reason = trim($request->input('reason', ''));
            if (empty($reason)) {
                return response()->json(['success' => false, 'message' => 'Vui lòng chọn lý do từ chối.'], 422);
            }

            $note = trim($request->input('note', ''));

            $property->update([
                'status'           => 2,
                'rejection_reason' => $reason,
                'rejection_note'   => $note ?: null,
            ]);

            $broker = $property->agent;
            if ($broker && $broker->telegram_id) {
                $title    = $property->title ?? 'BĐS';
                $noteText = $note ? "\n📝 Ghi chú: {$note}" : '';
                $message  = "❌ *TIN BĐS CHƯA ĐƯỢC DUYỆT*\n"
                    . "────────────────\n"
                    . "🏠 {$title}\n"
                    . "⚠️ Lý do: {$reason}{$noteText}\n"
                    . "💡 Vui lòng bổ sung và gửi lại.";
                app(NotificationService::class)->sendToCustomer($broker, $message);
            }

            // In-app notification to broker
            if ($broker) {
                app(InAppNotificationService::class)->notify($broker, 'property_rejected', 'property', 'status', [
                    'title' => 'BĐS của bạn chưa được duyệt',
                    'body'  => ($property->title ?? 'BĐS') . ' — Lý do: ' . $reason,
                    'notifiable_type' => Property::class,
                    'notifiable_id'   => $property->id,
                    'actor_id'        => Auth::guard('webapp')->id(),
                    'data'  => [
                        'property_id' => $property->id,
                        'title'       => $property->title,
                        'reason'      => $reason,
                        'note'        => $note ?: null,
                    ],
                ]);
            }

            return response()->json([
                'success'       => true,
                'pending_count' => Property::where('status', 0)->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('adminRejectProperty error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống.'], 500);
        }
    }

    // ─── Admin Commission Approval API ───────────────────────────────────────

    public function adminCommissionsApi(Request $request): JsonResponse
    {
        $tab = $request->input('tab', 'pending');

        // Stats (across all statuses, excluding cancelled)
        $pendingCount    = CrmDealCommission::where('status', CommissionStatus::PENDING_DEPOSIT)->count();
        $processingCount = CrmDealCommission::whereIn('status', [CommissionStatus::DEPOSITED, CommissionStatus::NOTARIZING])->count();

        $monthlyTotal = CrmDealCommission::whereIn('status', [
            CommissionStatus::DEPOSITED,
            CommissionStatus::NOTARIZING,
            CommissionStatus::COMPLETED,
        ])
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->get()
            ->sum(function ($c) {
                return (float) $c->getRawOriginal('sale_commission')
                    + (float) $c->getRawOriginal('app_commission')
                    + (float) $c->getRawOriginal('owner_commission');
            });

        $stats = [
            'pending_count'       => $pendingCount,
            'processing_count'    => $processingCount,
            'monthly_total_trieu' => round($monthlyTotal / 1_000_000, 1),
            'waiting_deposit'     => $pendingCount,
        ];

        // Build query per tab
        $query = CrmDealCommission::with(['deal.customer', 'sale', 'property.agent'])
            ->orderByDesc('created_at');

        if ($tab === 'processing') {
            $query->whereIn('status', [CommissionStatus::DEPOSITED, CommissionStatus::NOTARIZING]);
        } elseif ($tab === 'completed') {
            $query->where('status', CommissionStatus::COMPLETED);
        } else {
            $query->where('status', CommissionStatus::PENDING_DEPOSIT);
        }

        $commissions = $query->get()->map(function (CrmDealCommission $c) {
            $saleCom   = (float) $c->getRawOriginal('sale_commission');
            $appCom    = (float) $c->getRawOriginal('app_commission');
            $ownerCom  = (float) $c->getRawOriginal('owner_commission');
            $total     = $saleCom + $appCom + $ownerCom;

            $deal      = $c->deal;
            $dealAmt   = $deal ? (float) $deal->getRawOriginal('amount') : 0;
            $commPct   = ($dealAmt > 0 && $total > 0) ? round($total / $dealAmt * 100, 1) : 0;

            $property  = $c->property;
            $broker    = $property?->agent;

            $saleComTrieu  = round($saleCom  / 1_000_000, 1);
            $appComTrieu   = round($appCom   / 1_000_000, 1);
            $ownerComTrieu = round($ownerCom / 1_000_000, 1);
            $totalTrieu    = round($total    / 1_000_000, 1);
            $dealAmtTrieu  = round($dealAmt  / 1_000_000, 1);

            $salePct  = $total > 0 ? round($saleCom  / $total * 100) : 0;
            $appPct   = $total > 0 ? round($appCom   / $total * 100) : 0;
            $ownerPct = $total > 0 ? (100 - $salePct - $appPct) : 0;

            return [
                'id'                    => $c->id,
                'status'                => $c->getRawOriginal('status'),
                'notes'                 => $c->notes,
                'deposit_expected_date' => $c->deposit_expected_date
                    ? Carbon::parse($c->deposit_expected_date)->format('d/m/Y')
                    : null,
                'sale_commission_trieu' => $saleComTrieu,
                'app_commission_trieu'  => $appComTrieu,
                'owner_commission_trieu'=> $ownerComTrieu,
                'total_trieu'           => $totalTrieu,
                'deal_amount_trieu'     => $dealAmtTrieu,
                'comm_pct'              => $commPct,
                'sale_pct'              => $salePct,
                'app_pct'               => $appPct,
                'owner_pct'             => $ownerPct,
                'deal_id'               => $c->deal_id,
                'customer_name'         => $deal?->customer?->name ?? 'Khách hàng',
                'sale_name'             => $c->sale?->name ?? 'Sale',
                'broker_name'           => $broker?->name ?? null,
                'property_title'        => $property?->title ?? 'BĐS #' . $c->property_id,
                'created_at_fmt'        => Carbon::parse($c->getRawOriginal('created_at'))->format('d/m/Y'),
            ];
        });

        return response()->json([
            'success'     => true,
            'stats'       => $stats,
            'commissions' => $commissions,
            'tab'         => $tab,
        ]);
    }

    public function adminApproveCommission(int $id): JsonResponse
    {
        try {
            $commission = CrmDealCommission::with(['sale', 'property'])->findOrFail($id);

            if ($commission->getRawOriginal('status') !== CommissionStatus::PENDING_DEPOSIT->value) {
                return response()->json(['success' => false, 'message' => 'Hoa hồng này không ở trạng thái chờ duyệt.'], 422);
            }

            $commission->status = CommissionStatus::DEPOSITED;
            $commission->save();

            // Notify sale person
            $propTitle  = $commission->property?->title ?? 'BĐS';
            $totalTrieu = round(
                ((float) $commission->getRawOriginal('sale_commission')
                + (float) $commission->getRawOriginal('app_commission')
                + (float) $commission->getRawOriginal('owner_commission')) / 1_000_000,
                1
            );

            if ($commission->sale && $commission->sale->telegram_id) {
                $message = "✅ *HOA HỒNG ĐÃ ĐƯỢC DUYỆT*\n"
                    . "🏠 {$propTitle}\n"
                    . "💰 Tổng HH: {$totalTrieu} triệu\n"
                    . "📌 Trạng thái: Chờ đặt cọc\n"
                    . "Admin đã xác nhận — Vui lòng tiến hành thu cọc.";
                app(NotificationService::class)->sendToUser($commission->sale, $message);
            }

            // In-app notification — find Customer matching the User's telegram_id
            if ($commission->sale && $commission->sale->telegram_id) {
                $saleCustomer = Customer::where('telegram_id', $commission->sale->telegram_id)->first();
                if ($saleCustomer) {
                    app(InAppNotificationService::class)->notify($saleCustomer, 'commission_status', 'commission', 'status', [
                        'title' => 'Hoa hồng đã được duyệt',
                        'body'  => $propTitle . ' — Tổng HH: ' . $totalTrieu . ' triệu. Trạng thái: Chờ đặt cọc.',
                        'notifiable_type' => CrmDealCommission::class,
                        'notifiable_id'   => $commission->id,
                        'actor_id'        => Auth::guard('webapp')->id(),
                        'data'  => [
                            'commission_id' => $commission->id,
                            'property_title' => $propTitle,
                            'total_million'  => $totalTrieu,
                            'status'         => 'deposited',
                        ],
                    ]);
                }
            }

            $pendingCount = CrmDealCommission::where('status', CommissionStatus::PENDING_DEPOSIT)->count();

            return response()->json([
                'success'       => true,
                'message'       => 'Đã duyệt hoa hồng.',
                'pending_count' => $pendingCount,
            ]);
        } catch (\Exception $e) {
            Log::error('adminApproveCommission error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống.'], 500);
        }
    }

    public function adminAdvanceCommission(int $id): JsonResponse
    {
        try {
            $commission = CrmDealCommission::with(['sale', 'property'])->findOrFail($id);
            $currentStatus = $commission->getRawOriginal('status');

            $transitions = [
                CommissionStatus::DEPOSITED->value  => CommissionStatus::NOTARIZING,
                CommissionStatus::NOTARIZING->value => CommissionStatus::COMPLETED,
            ];

            if (!isset($transitions[$currentStatus])) {
                return response()->json(['success' => false, 'message' => 'Không thể chuyển trạng thái này.'], 422);
            }

            $newStatus = $transitions[$currentStatus];
            $commission->status = $newStatus;
            $commission->save();

            // Notify sale person
            $propTitle  = $commission->property?->title ?? 'BĐS';
            $statusLabel = $newStatus->label();

            if ($commission->sale && $commission->sale->telegram_id) {
                $message = "📋 *CẬP NHẬT HOA HỒNG*\n"
                    . "🏠 {$propTitle}\n"
                    . "📌 Trạng thái mới: {$statusLabel}\n"
                    . "Admin đã xác nhận tiến độ giao dịch.";
                app(NotificationService::class)->sendToUser($commission->sale, $message);
            }

            // In-app notification
            if ($commission->sale && $commission->sale->telegram_id) {
                $saleCustomer = Customer::where('telegram_id', $commission->sale->telegram_id)->first();
                $notifType = $newStatus === CommissionStatus::COMPLETED ? 'commission_completed' : 'commission_status';
                $title = $newStatus === CommissionStatus::COMPLETED
                    ? 'Hoa hồng đã hoàn tất!'
                    : 'Hoa hồng cập nhật: ' . $statusLabel;
                if ($saleCustomer) {
                    app(InAppNotificationService::class)->notify($saleCustomer, $notifType, 'commission', 'status', [
                        'title' => $title,
                        'body'  => $propTitle . ' — Trạng thái: ' . $statusLabel,
                        'notifiable_type' => CrmDealCommission::class,
                        'notifiable_id'   => $commission->id,
                        'actor_id'        => Auth::guard('webapp')->id(),
                        'data'  => [
                            'commission_id'  => $commission->id,
                            'property_title' => $propTitle,
                            'status'         => $newStatus->value,
                        ],
                    ]);
                }
            }

            return response()->json([
                'success'    => true,
                'message'    => 'Đã cập nhật trạng thái.',
                'new_status' => $newStatus->value,
            ]);
        } catch (\Exception $e) {
            Log::error('adminAdvanceCommission error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống.'], 500);
        }
    }

    public function adminHoldCommission(Request $request, int $id): JsonResponse
    {
        try {
            $commission = CrmDealCommission::with(['sale', 'property'])->findOrFail($id);

            $note = $request->input('note', '');
            if (!empty($note)) {
                $commission->notes = $note;
                $commission->save();
            }

            // Notify sale person
            if ($commission->sale && $commission->sale->telegram_id) {
                $propTitle = $commission->property?->title ?? 'BĐS';
                $noteText  = !empty($note) ? "\n📝 Ghi chú: {$note}" : '';
                $message   = "⏸ *HOA HỒNG ĐANG ĐƯỢC XEM XÉT*\n"
                    . "🏠 {$propTitle}\n"
                    . "⚠️ Admin đang giữ lại để kiểm tra thêm.{$noteText}\n"
                    . "Vui lòng chờ xác nhận.";
                app(NotificationService::class)->sendToUser($commission->sale, $message);
            }

            return response()->json(['success' => true, 'message' => 'Đã giữ lại để xem xét.']);
        } catch (\Exception $e) {
            Log::error('adminHoldCommission error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống.'], 500);
        }
    }

    // ─── Referral API ────────────────────────────────────────────────────────

    public function referralApi(Request $request)
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Lazy-generate referral code if missing
        if (empty($customer->referral_code)) {
            $customer->referral_code = Customer::generateReferralCode();
            $customer->save();
        }

        $botUsername = config('services.telegram.bot_username', 'DalatBDSBot');
        $webappShortName = config('services.telegram.webapp_short_name', 'dangtin');

        $shareUrl = "https://t.me/{$botUsername}/{$webappShortName}?startapp=ref_{$customer->referral_code}";
        $telegramShareUrl = 'https://t.me/share/url?url=' . urlencode($shareUrl)
            . '&text=' . urlencode('Tham gia Đà Lạt BĐS với mã giới thiệu ' . $customer->referral_code . '. Đăng ký ngay!');

        $now       = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd   = $now->copy()->endOfMonth();

        // Direct referrals
        $referrals = Customer::where('referred_by', $customer->id)->get();
        $referralIds = $referrals->pluck('id')->toArray();

        // Active: referrals who have commission deals this month
        $activeIds = [];
        if (!empty($referralIds)) {
            $activeIds = CrmDealCommission::whereHas('deal.lead', function ($q) use ($referralIds) {
                $q->whereIn('user_id', $referralIds)->orWhereIn('sale_id', $referralIds);
            })
                ->whereBetween('crm_deals_commissions.created_at', [$monthStart, $monthEnd])
                ->where('crm_deals_commissions.status', '!=', CommissionStatus::CANCELLED->value)
                ->join('crm_deals', 'crm_deals_commissions.deal_id', '=', 'crm_deals.id')
                ->join('crm_leads', 'crm_deals.lead_id', '=', 'crm_leads.id')
                ->selectRaw('COALESCE(crm_leads.user_id, crm_leads.sale_id) as cid')
                ->pluck('cid')
                ->unique()
                ->toArray();
        }

        // Month earned: 5% of referrals' sale_commission in current month (completed)
        $monthEarned = 0;
        if (!empty($referralIds)) {
            $monthEarned = (float) CrmDealCommission::whereHas('deal.lead', function ($q) use ($referralIds) {
                $q->whereIn('user_id', $referralIds)->orWhereIn('sale_id', $referralIds);
            })
                ->whereBetween('updated_at', [$monthStart, $monthEnd])
                ->where('status', CommissionStatus::COMPLETED->value)
                ->sum('sale_commission');
            $monthEarned = round($monthEarned * 0.05 / 1_000_000, 1);
        }

        // Build referral tree
        $avatarColors = ['#059669', '#7c3aed', '#2563eb', '#d97706', '#0d9488', '#dc2626', '#0891b2'];
        $tree = $referrals->map(function ($ref, $idx) use ($activeIds, $monthStart, $monthEnd, $avatarColors) {
            $refId = $ref->id;
            $monthEarnedRef = (float) CrmDealCommission::whereHas('deal.lead', function ($q) use ($refId) {
                $q->where('user_id', $refId)->orWhere('sale_id', $refId);
            })
                ->whereBetween('updated_at', [$monthStart, $monthEnd])
                ->where('status', CommissionStatus::COMPLETED->value)
                ->sum('sale_commission');

            $roleLabel = match ($ref->role ?? 'broker') {
                'sale'       => 'Sale',
                'sale_admin' => 'Sale Admin',
                'bds_admin'  => 'BĐS Admin',
                'admin'      => 'Admin',
                default      => 'eBroker',
            };

            $ward = null;
            try {
                $ward = $ref->property()->distinct()->first()?->ward_code
                    ? LocationsWard::where('code', $ref->property()->distinct()->first()->ward_code)->first()?->name
                    : null;
            } catch (\Exception $e) {}

            return [
                'name'              => $ref->name ?? 'Thành viên',
                'role_label'        => $roleLabel,
                'joined_at'         => $ref->created_at ? $ref->created_at->format('d/m/Y') : '',
                'ward_name'         => $ward ?? 'Chưa có khu vực',
                'is_active'         => in_array($refId, $activeIds),
                'month_earned_trieu'=> round($monthEarnedRef * 0.05 / 1_000_000, 1),
                'avatar_letter'     => strtoupper(substr($ref->name ?? 'U', 0, 1)),
                'avatar_color'      => $avatarColors[$idx % count($avatarColors)],
            ];
        })->values()->toArray();

        // Build history: all commission records of referrals
        $history = [];
        if (!empty($referralIds)) {
            $commissions = CrmDealCommission::with(['deal.lead', 'deal.customer', 'property'])
                ->whereHas('deal.lead', function ($q) use ($referralIds) {
                    $q->whereIn('user_id', $referralIds)->orWhereIn('sale_id', $referralIds);
                })
                ->where('status', '!=', CommissionStatus::CANCELLED->value)
                ->orderBy('updated_at', 'desc')
                ->limit(50)
                ->get();

            foreach ($commissions as $comm) {
                $deal = $comm->deal;
                $lead = $deal?->lead;
                if (!$lead) continue;

                $refCustomerId = $lead->user_id ?? $lead->sale_id;
                $refCustomer   = $referrals->firstWhere('id', $refCustomerId);
                $baseAmount    = (float) $comm->getRawOriginal('sale_commission');
                $refAmount     = round($baseAmount * 0.05 / 1_000_000, 2);

                $statusLabel = match ($comm->status->value ?? $comm->status) {
                    CommissionStatus::COMPLETED->value      => 'completed',
                    CommissionStatus::DEPOSITED->value,
                    CommissionStatus::NOTARIZING->value     => 'pending',
                    CommissionStatus::CANCELLED->value      => 'cancelled',
                    default                                  => 'upcoming',
                };

                $propertyLabel = $comm->property?->title ?? ('Deal #' . ($deal->id ?? '?'));
                if ($comm->property && $comm->property->ward_code) {
                    $wardName = LocationsWard::where('code', $comm->property->ward_code)->first()?->name;
                    if ($wardName) $propertyLabel .= ' · ' . $wardName;
                }

                $history[] = [
                    'referee_name'           => $refCustomer?->name ?? 'Thành viên',
                    'deal_id'                => $deal->id ?? null,
                    'property_label'         => $propertyLabel,
                    'date'                   => $comm->updated_at?->format('d/m/Y') ?? '',
                    'base_commission_trieu'  => round($baseAmount / 1_000_000, 1),
                    'referral_amount_trieu'  => $refAmount,
                    'status'                 => $statusLabel,
                ];
            }
        }

        return response()->json([
            'referral_code'      => $customer->referral_code,
            'share_url'          => $shareUrl,
            'telegram_share_url' => $telegramShareUrl,
            'stats'              => [
                'total_referrals'  => count($referralIds),
                'active_referrals' => count($activeIds),
                'month_earned_trieu' => $monthEarned,
                'month_label'      => 'Tháng ' . $now->month . '/' . $now->year,
            ],
            'tree'    => $tree,
            'history' => $history,
        ]);
    }

    public function referralLanding(string $code)
    {
        $referrer = Customer::where('referral_code', $code)->first();
        if (!$referrer) {
            abort(404);
        }

        // Store in session for attribution when new user registers
        session(['referral_code' => $code]);

        return view('webapp.referral-landing', compact('referrer', 'code'));
    }

    /**
     * GET /share/p/{id}
     * Smart redirect: browser → public property page (/bds/{slug})
     */
    public function propertyShareRedirect($id)
    {
        $property = Property::findOrFail($id);

        return redirect(route('bds.show', $property->slug));
    }

    /**
     * POST /webapp/auth
     * Form-based auth: validates Telegram initData, logs in via session, redirects.
     * Dùng form POST thay vì fetch+reload để session cookie được set trong navigation response,
     * tránh vấn đề iOS WKWebView không persist cookie từ XHR response kịp thời.
     */
    public function authRedirect(Request $request)
    {
        $initData     = $request->input('initData', '');
        $referralCode = $request->input('referral_code', '');
        $retry        = (int) $request->input('retry', 0);

        if (!$initData) {
            return redirect('/webapp?login_status=error');
        }

        $telegramUserData = $this->validateTelegramInitData($initData);
        if (!$telegramUserData) {
            \Log::warning('WebApp authRedirect: Telegram initData validation failed');
            return redirect('/webapp?login_status=error');
        }

        $telegramId = $telegramUserData['id'];
        $customer   = \App\Models\Customer::where('telegram_id', $telegramId)->first();

        if ($customer) {
            // Gán referrer nếu user chưa có referred_by và có referral_code
            if (!empty($referralCode) && empty($customer->referred_by)) {
                $referrer = \App\Models\Customer::where('referral_code', $referralCode)->first();
                if ($referrer && $referrer->id !== $customer->id) {
                    $customer->referred_by = $referrer->id;
                    $customer->save();
                    \Log::info("Referral assigned via authRedirect: Customer #{$customer->id} referred by #{$referrer->id}");
                }
            }

            // Thay vì redirect (302 → GET /webapp), render page trực tiếp từ POST response.
            // Lý do: Telegram WebView (cả Android lẫn iOS) đôi khi không gửi đúng session
            // cookie khi follow redirect sau form POST, khiến server tạo session mới và
            // không nhận ra user → loop vô hạn.
            // Giải pháp PRG thay thế: set user vào guard + session, render index() ngay tại đây.
            // JS trong layout sẽ dùng history.replaceState để sửa URL từ /webapp/auth → /webapp.
            Auth::guard('webapp')->setUser($customer); // set in-memory cho request này
            $request->session()->put(Auth::guard('webapp')->getName(), $customer->id);
            $request->session()->save(); // flush để các request sau (refresh) cũng có session
            return $this->index($request);
        }

        // Chưa có user → cache referral code để Bot dùng khi tạo account
        if (!empty($referralCode) && !empty($telegramId)) {
            \Cache::put("pending_referral:{$telegramId}", $referralCode, now()->addHours(24));
        }

        return redirect('/webapp?login_status=guest&retry=' . $retry);
    }
}