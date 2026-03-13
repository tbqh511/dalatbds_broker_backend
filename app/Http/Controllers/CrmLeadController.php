<?php

namespace App\Http\Controllers;

use App\Http\Requests\Crm\Lead\StoreLeadRequest;
use App\Http\Requests\Crm\Lead\UpdateLeadRequest;
use App\Models\CrmDeal;
use App\Models\CrmLeadActivity;
use App\Models\Customer;
use App\Services\CrmLeadService;
use App\Services\NotificationService;
use App\Services\Telegram\TelegramMessageTemplates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CrmLead;
use App\Models\Category;
use App\Models\LocationsWard;

class CrmLeadController extends Controller
{
    protected $leadService;
    protected $notificationService;

    public function __construct(CrmLeadService $leadService, NotificationService $notificationService)
    {
        $this->leadService = $leadService;
        $this->notificationService = $notificationService;
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
                    'budget'           => format_vnd($lead->demand_rate_min) . ' – ' . format_vnd($lead->demand_rate_max),
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

        $newStatus = $request->input('status');
        $oldStatus = $lead->getRawOriginal('status');
        $lead->status = $newStatus;
        $lead->save();

        CrmLeadActivity::create([
            'lead_id'  => $lead->id,
            'actor_id' => $customer->id,
            'type'     => 'status_change',
            'content'  => "Đổi trạng thái: {$oldStatus} → {$newStatus}",
        ]);

        return response()->json(['success' => true]);
    }

    public function assignSale(Request $request, $id)
    {
        $customer = Auth::guard('webapp')->user();
        $lead = $this->leadService->getLead($id);

        if (!$lead || !$customer->isSaleAdmin()) {
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

        $lead->sale_id = $sale->id;
        $lead->save();

        CrmLeadActivity::create([
            'lead_id'  => $lead->id,
            'actor_id' => $customer->id,
            'type'     => 'assignment',
            'content'  => "Phân công cho: {$sale->name}",
        ]);

        // Notify assigned sale via Telegram
        if ($sale->telegram_id) {
            $message = TelegramMessageTemplates::leadAssigned($lead);
            $this->notificationService->sendToCustomer($sale, $message);
        }

        return response()->json(['success' => true, 'sale_name' => $sale->name]);
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
}
