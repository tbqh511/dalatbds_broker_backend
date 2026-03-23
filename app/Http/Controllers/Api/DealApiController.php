<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CrmCustomer;
use App\Models\CrmDeal;
use App\Models\CrmLead;
use App\Models\CrmDealAssigned;
use App\Models\User;
use App\Models\Customer;
use App\Services\InAppNotificationService;
use App\Services\NotificationService;
use App\Services\Telegram\TelegramMessageTemplates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DealApiController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Task 5.3: List Deals
     * GET /api/deals
     */
    public function index(Request $request)
    {
        // Check Auth (Assuming Token or Sale ID passed)
        // In real app, use Auth::id() or Auth::guard('api')->id()
        $user = $request->user();
        $saleId = $user ? $user->id : $request->sale_id;

        $query = CrmDeal::query()->with(['customer', 'products', 'assigneds.sale']);

        // Filter by Sale (if not Admin, or if requested)
        if ($saleId) {
            // Check if sale is assigned to deal
            $query->whereHas('assigneds', function ($q) use ($saleId) {
                $q->where('user_id', $saleId);
            });
        }

        // Filter by Status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Date Range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $deals = $query->orderBy('updated_at', 'desc')->paginate(10);

        // Append counts
        $deals->getCollection()->transform(function ($deal) {
            $deal->products_count = $deal->products->count();
            // Assuming bookings are related to products
            $deal->bookings_count = $deal->products->sum(function ($product) {
                    return $product->bookings()->count();
                }
                );
                return $deal;
            });

        return response()->json([
            'success' => true,
            'data' => $deals
        ]);
    }

    /**
     * Task 5.4: Deal Details
     * GET /api/deals/{id}
     */
    public function show($id)
    {
        $deal = CrmDeal::with([
            'customer',
            'lead',
            'assigneds.sale',
            'products.property',
            'products.bookings',
            'commissions'
        ])->find($id);

        if (!$deal) {
            return response()->json(['success' => false, 'message' => 'Deal not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $deal
        ]);
    }

    /**
     * Task 6.4: WebApp Deal Details with Products
     * GET /telegram/deals/{id}
     */
    public function showWebApp($id)
    {
        $deal = CrmDeal::with([
            'customer',
            'products.property',
            'products.bookings' => function ($q) {
            $q->latest()->limit(1);
        }
        ])->findOrFail($id);

        return view('telegram.deals.show', compact('deal'));
    }

    /**
     * Task 5.1: Create Deal
     * POST /api/deals
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required_without:lead_id|exists:crm_customers,id',
            'lead_id' => 'nullable|exists:crm_leads,id',
            'sale_id' => 'required|exists:users,id', // The sale creating/assigned to the deal
            'amount' => 'nullable|numeric',
            'notes' => 'nullable|string',
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
            $leadId = $request->lead_id;
            $customerId = $request->customer_id;

            // If lead_id is provided, ensure customer_id matches or retrieve it
            if ($leadId) {
                $lead = CrmLead::find($leadId);
                if (!$customerId) {
                    $customerId = $lead->customer_id;
                }

                // Update Lead Status
                $lead->status = 'converted';
                $lead->save();
            }

            // Create Deal
            $deal = CrmDeal::create([
                'customer_id' => $customerId,
                'lead_id' => $leadId,
                'status' => 'new', // Or 'prospecting' as default
                'amount' => $request->amount ?? 0,
                'notes' => $request->notes,
                'last_updated_by' => $request->sale_id,
            ]);

            // Assign Sale to Deal
            CrmDealAssigned::create([
                'deal_id' => $deal->id,
                'user_id' => $request->sale_id,
                'note' => 'Creator / Initial Assignment',
            ]);

            DB::commit();

            // Task 5.2: Notification
            $this->notifyDealCreation($deal, $request->sale_id);

            return response()->json([
                'success' => true,
                'message' => 'Deal created successfully',
                'data' => $deal
            ], 201);

        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating deal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Task 5.2: Notification Logic
     */
    protected function notifyDealCreation($deal, $saleId)
    {
        // Load relationships needed for template
        $deal->load(['customer', 'assigneds.sale']);
        $sale = User::find($saleId);
        $customer = $deal->customer;

        // Dùng template tập trung — chỉ sửa TelegramMessageTemplates.php để thay đổi nội dung
        $message = TelegramMessageTemplates::dealCreated($deal);

        // 1. Notify Public Group (e.g. Company Channel)
        $this->notificationService->sendToGroup('public_channel', $message);

        // 2. Notify Sale Admin Group
        $this->notificationService->sendToGroup('sale_admin', $message);

        // 3. Notify Assigned Sale (if they have telegram_id)
        if ($sale && $sale->telegram_id) {
            $saleMsg = "🎉 Bạn được giao Deal mới \#{$deal->id}\n\n" . $message;
            $this->notificationService->sendToUser($sale, $saleMsg);
        }

        // 4. Notify Customer (xác nhận đơn giản, không lộ thông tin nội bộ)
        if ($customer && $customer->telegram_id) {
            $customerMsg = "Xin chào {$customer->full_name}, hồ sơ giao dịch của bạn đã được khởi tạo. Mã hồ sơ: \#{$deal->id}. Chúng tôi sẽ sớm liên hệ lại.";
            $this->notificationService->sendToCustomer($customer, $customerMsg);
        }

        // 5. In-app notifications for sale + broker
        $inApp = app(InAppNotificationService::class);
        $lead = $deal->lead;
        $dealTitle = 'Deal #' . $deal->id . ' — ' . ($customer->full_name ?? 'N/A');

        // Notify sale (find Customer by telegram_id matching the User)
        if ($sale && $sale->telegram_id) {
            $saleCustomer = Customer::where('telegram_id', $sale->telegram_id)->first();
            if ($saleCustomer) {
                $inApp->notify($saleCustomer, 'deal_created', 'deal', 'status', [
                    'title' => 'Deal mới được tạo',
                    'body'  => $dealTitle,
                    'notifiable_type' => CrmDeal::class,
                    'notifiable_id'   => $deal->id,
                    'data'  => ['deal_id' => $deal->id, 'customer_name' => $customer->full_name ?? ''],
                ]);
            }
        }

        // Notify broker who created the lead
        if ($lead && $lead->user_id) {
            $broker = Customer::find($lead->user_id);
            if ($broker && (!$sale || $broker->telegram_id !== $sale->telegram_id)) {
                $inApp->notify($broker, 'deal_created', 'deal', 'status', [
                    'title' => 'Deal mới được tạo từ lead của bạn',
                    'body'  => $dealTitle,
                    'notifiable_type' => CrmDeal::class,
                    'notifiable_id'   => $deal->id,
                    'data'  => ['deal_id' => $deal->id, 'customer_name' => $customer->full_name ?? ''],
                ]);
            }
        }
    }
}