<?php

namespace App\Http\Controllers;

use App\Http\Requests\Crm\Lead\StoreLeadRequest;
use App\Http\Requests\Crm\Lead\UpdateLeadRequest;
use App\Services\CrmLeadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CrmLead;

class CrmLeadController extends Controller
{
    protected $leadService;

    public function __construct(CrmLeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    public function index(Request $request)
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer) {
            return redirect()->route('webapp'); // Or login page
        }

        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'lead_type' => $request->input('lead_type'),
        ];

        $leads = $this->leadService->getLeads($customer->id, 10, $filters);

        return view('frontend_dashboard_leads', compact('leads', 'customer'));
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
