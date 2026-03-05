<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CrmDealCommission;
use App\Models\CrmDeal;
use App\Models\User;
use App\Enums\CommissionStatus;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CommissionApiController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Task 8.1: Create Commission
     * POST /api/deals/{id}/commission
     */
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'sale_id' => 'required|exists:users,id',
            'property_id' => 'required|exists:propertys,id',
            'sale_commission' => 'required|numeric|min:0',
            'app_commission' => 'nullable|numeric|min:0',
            'lead_commission' => 'nullable|numeric|min:0',
            'owner_commission' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $deal = CrmDeal::find($id);
        if (!$deal) {
            return response()->json(['success' => false, 'message' => 'Deal not found'], 404);
        }

        DB::beginTransaction();
        try {
            $commission = CrmDealCommission::create([
                'deal_id' => $id,
                'sale_id' => $request->sale_id,
                'property_id' => $request->property_id,
                'sale_commission' => $request->sale_commission,
                'app_commission' => $request->app_commission ?? 0,
                'lead_commission' => $request->lead_commission ?? 0,
                'owner_commission' => $request->owner_commission ?? 0,
                'notes' => $request->notes,
                'status' => CommissionStatus::PENDING_DEPOSIT,
            ]);

            // Task 8.3: Notification
            $this->notifyCommission($commission, 'created');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Commission created successfully',
                'data' => $commission
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Task 8.2: Update Commission Status
     * PATCH /api/commissions/{id}
     */
    public function update(Request $request, $id)
    {
        $validStatuses = array_column(CommissionStatus::cases(), 'value');
        $validStatusesStr = implode(',', $validStatuses);

        $validator = Validator::make($request->all(), [
            'status' => "required|in:$validStatusesStr",
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $commission = CrmDealCommission::with(['sale', 'property'])->find($id);
        if (!$commission) {
            return response()->json(['success' => false, 'message' => 'Commission not found'], 404);
        }

        try {
            // Validate Flow
            $currentStatus = $commission->status;
            $newStatus = CommissionStatus::from($request->status);

            // Define allowed transitions
            // pending_deposit -> deposited -> notarizing -> completed
            // Any -> cancelled
            $allowed = false;
            
            if ($newStatus === CommissionStatus::CANCELLED) {
                $allowed = true;
            } elseif ($currentStatus === CommissionStatus::PENDING_DEPOSIT && $newStatus === CommissionStatus::DEPOSITED) {
                $allowed = true;
            } elseif ($currentStatus === CommissionStatus::DEPOSITED && $newStatus === CommissionStatus::NOTARIZING) {
                $allowed = true;
            } elseif ($currentStatus === CommissionStatus::NOTARIZING && $newStatus === CommissionStatus::COMPLETED) {
                $allowed = true;
            } elseif ($currentStatus === $newStatus) {
                $allowed = true; // No change
            }

            if (!$allowed) {
                return response()->json([
                    'success' => false, 
                    'message' => "Invalid status transition from {$currentStatus->label()} to {$newStatus->label()}"
                ], 400);
            }

            $commission->status = $newStatus;
            if ($request->has('notes')) {
                $commission->notes = $request->notes;
            }
            $commission->save();

            // Task 8.3: Notification
            $this->notifyCommission($commission, 'updated');

            return response()->json([
                'success' => true,
                'message' => 'Commission updated successfully',
                'data' => $commission
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Task 8.4: Commission Report
     * GET /api/commissions/report
     */
    public function report(Request $request)
    {
        $saleId = $request->sale_id ?? ($request->user()->id ?? null);
        
        $query = CrmDealCommission::query()->with(['sale', 'property']);

        if ($saleId) {
            $query->where('sale_id', $saleId);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $commissions = $query->orderBy('created_at', 'desc')->get();

        // Aggregation
        $totalAmount = $commissions->sum('sale_commission'); // Actually string formatted in model accessor, need raw
        // Accessor interferes with sum if using collection sum on attribute. 
        // Better use DB query for sum or remove formatting from accessor (or use raw attribute).
        // Since getSaleCommissionAttribute formats it, $commission->sale_commission returns string.
        // Let's use DB aggregate for total.
        
        $totalQuery = CrmDealCommission::query();
        if ($saleId) $totalQuery->where('sale_id', $saleId);
        if ($request->has('start_date') && $request->has('end_date')) $totalQuery->whereBetween('created_at', [$request->start_date, $request->end_date]);
        if ($request->has('status')) $totalQuery->where('status', $request->status);
        
        $totalRaw = $totalQuery->sum('sale_commission');
        
        // Group by status
        $byStatus = $totalQuery->select('status', DB::raw('SUM(sale_commission) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->map(function($item) {
                return [
                    'status' => $item->status,
                    'status_label' => CommissionStatus::tryFrom($item->status)?->label() ?? $item->status,
                    'total' => $item->total,
                    'count' => $item->count
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'total_amount' => $totalRaw,
                    'total_deals' => $commissions->count(),
                    'by_status' => $byStatus
                ],
                'details' => $commissions
            ]
        ]);
    }

    /**
     * Task 8.3: Notification Logic
     */
    protected function notifyCommission($commission, $type)
    {
        // Ensure relationships loaded
        $commission->load(['sale', 'property']);
        
        $sale = $commission->sale;
        $property = $commission->property;
        
        $title = $type === 'created' ? '💰 HOA HỒNG MỚI' : '📊 CẬP NHẬT HOA HỒNG';
        
        // Using getAttributes to avoid accessor formatting for calculation if needed, 
        // but for display accessor is fine if it returns formatted string.
        // Model accessor returns "1,000,000.00".
        
        $message = "{$title}\n\n";
        $message .= "🏠 BĐS: " . ($property->title ?? 'N/A') . "\n";
        $message .= "📍 Địa chỉ: " . ($property->address ?? 'N/A') . "\n";
        $message .= "💵 Số tiền: " . $commission->sale_commission . " VNĐ\n"; // Accessor used
        $message .= "📊 Trạng thái: " . $commission->status->label() . "\n";
        
        if ($commission->notes) {
            $message .= "📝 Ghi chú: {$commission->notes}\n";
        }

        // Notify Sale
        if ($sale && $sale->telegram_id) {
            $this->notificationService->sendToUser($sale, $message);
        }

        // Notify Sale Admin Group
        $this->notificationService->sendToGroup('sale_admin', $message);
    }

    /**
     * Task 8.5: WebApp View
     */
    public function showWebApp(Request $request)
    {
        // Assuming Admin check logic or user role
        // For now, list commissions for the logged-in user (Sale)
        // Admin view logic can be added later or via filter
        
        // If loaded via web route with middleware
        // $user = $request->user();
        // Just return view, data loaded via API in view
        return view('telegram.commissions.index');
    }
}
