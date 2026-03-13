<?php

namespace App\Http\Controllers;

use App\Models\CrmLead;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
