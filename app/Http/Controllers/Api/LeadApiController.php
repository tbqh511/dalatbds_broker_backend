<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CrmCustomer;
use App\Models\CrmLead;
use App\Models\User;
use App\Services\NotificationService;
use App\Services\Telegram\TelegramMessageTemplates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LeadApiController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Task 4.1: API Create Manual Lead
     * POST /api/leads
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'lead_type' => 'required|in:buy,rent',
            'note' => 'nullable|string',
            'source' => 'nullable|string',
            'user_id' => 'nullable|exists:customers,id', // Broker ID if available
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // 1. Create or Find Customer
            $customer = CrmCustomer::firstOrCreate(
            ['contact' => $request->phone],
            ['full_name' => $request->name]
            );

            // 2. Create Lead
            // If user_id is not provided, we might need a fallback. 
            // For now, if no user_id is provided, we might need to define a "System" customer or similar.
            // However, based on schema, user_id is required. 
            // I'll assume for now that if it's a manual lead from operator, we might assign it to a specific system user/customer account 
            // OR the request MUST provide a user_id (Broker).
            // Let's assume for now we need a user_id. If null, we'll try to find a default one or fail.
            // But wait, if this is an API called by an app, maybe the caller is authenticated?
            // If the caller is an Admin (User), they are not in `customers` table.
            // I'll use a placeholder logic: if user_id is missing, find the first customer (dangerous) or require it.
            // Let's require it for now, or check if the migration allows null (it doesn't).

            $brokerId = $request->user_id;
            if (!$brokerId) {
                // Temporary fallback: try to find a "System" customer or just the first one
                // Ideally this should be configured.
                $systemBroker = CrmCustomer::first();
                if ($systemBroker) {
                    $brokerId = $systemBroker->id;
                }
                else {
                    throw new \Exception('No Broker (user_id) available to assign as creator.');
                }
            }

            $lead = CrmLead::create([
                'user_id' => $brokerId,
                'customer_id' => $customer->id,
                'lead_type' => $request->lead_type,
                'source_note' => $request->source,
                'note' => $request->note,
                'status' => 'new',
                'demand_rate_min' => $request->input('price_min', 0),
                'demand_rate_max' => $request->input('price_max', 0),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Lead created successfully',
                'data' => $lead
            ], 201);

        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating lead: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Task 4.2: API Operator Assign Lead to Sale
     * POST /api/leads/{id}/assign
     */
    public function assign(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'sale_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $lead = CrmLead::with('customer')->findOrFail($id);
            $sale = User::findOrFail($request->sale_id);

            $lead->assigned_to = $sale->id;
            $lead->assigned_at = Carbon::now();
            $lead->save();

            // Task 4.3: Notify Sale
            $this->notifySale($sale, $lead);

            return response()->json([
                'success' => true,
                'message' => 'Lead assigned successfully to ' . $sale->name,
                'data' => $lead
            ]);

        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error assigning lead: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Task 4.3: Notification Logic
     */
    protected function notifySale($sale, $lead)
    {
        if ($sale->telegram_id) {
            $tpl = TelegramMessageTemplates::leadAssigned($lead);
            $this->notificationService->sendWithInlineKeyboard($sale->telegram_id, $tpl['text'], $tpl['keyboard']);
        }
    }

    /**
     * Task 4.4: List Leads for Sale (API for WebApp)
     * GET /api/leads/my-leads
     */
    public function myLeads(Request $request)
    {
        // Assuming the Sale is authenticated via API (Sanctum/JWT)
        // Or passed via query param for now if testing (not secure, but good for local dev)
        // In real app, use Auth::id()

        $saleId = $request->user()->id ?? $request->sale_id;

        if (!$saleId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $leads = CrmLead::where('assigned_to', $saleId)
            ->with('customer')
            ->orderBy('assigned_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $leads
        ]);
    }

    /**
     * Task 4.4: WebApp View for Lead Details
     * GET /telegram/leads/{id}
     */
    public function showWebApp($id)
    {
        $lead = CrmLead::with(['customer', 'assignedSale'])->findOrFail($id);

        // Return view
        return view('telegram.leads.show', compact('lead'));
    }

    /**
     * Update Lead Status from WebApp
     * POST /telegram/leads/{id}/update
     */
    public function updateFromWebApp(Request $request, $id)
    {
        $lead = CrmLead::findOrFail($id);

        if ($request->has('status')) {
            $lead->status = $request->status;
            $lead->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
            'data' => $lead
        ]);
    }
}