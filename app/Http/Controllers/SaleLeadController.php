<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CrmLead;
use App\Models\LocationsWard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleLeadController extends Controller
{
    public function index(Request $request)
    {
        $customer = Auth::guard('webapp')->user();

        $query = CrmLead::with(['customer', 'sale'])
            ->where('sale_id', $customer->id)
            ->orderBy('created_at', 'desc');

        if ($request->input('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->input('search')) {
            $search = $request->input('search');
            $query->whereHas('customer', fn ($q) => $q
                ->where('full_name', 'like', "%{$search}%")
                ->orWhere('contact', 'like', "%{$search}%")
            );
        }

        $leads = $query->paginate(10);

        if ($request->ajax()) {
            $categoryMap  = Category::where('status', '1')->pluck('category', 'id');
            $districtCode = config('location.district_code');
            $wardMap      = LocationsWard::where('district_code', $districtCode)->pluck('full_name', 'code');
            $statusLabels = [
                'New' => 'Mới', 'Contacted' => 'Đã liên hệ',
                'Converted' => 'Chuyển đổi', 'Lost' => 'Thất bại',
            ];

            $data = $leads->map(function ($lead) use ($categoryMap, $wardMap, $statusLabels) {
                $catNames = collect($lead->categories ?? [])
                    ->map(fn ($id) => $categoryMap[$id] ?? null)->filter()->implode(', ');
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
                    'date'             => $lead->created_at->format('d/m/Y'),
                    'show_url'         => route('webapp.leads.show', $lead->id),
                ];
            });

            return response()->json([
                'leads'     => $data,
                'has_more'  => $leads->hasMorePages(),
                'next_page' => $leads->currentPage() + 1,
                'total'     => $leads->total(),
            ]);
        }

        return view('frontend_dashboard_sale_leads', compact('customer', 'leads'));
    }
}
