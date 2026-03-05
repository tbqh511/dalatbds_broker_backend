<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CrmDeal;
use App\Models\CrmDealProduct;
use App\Models\Property;
use App\Models\CrmCustomer;
use App\Services\NotificationService;
use App\Enums\DealsProductStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DealProductApiController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Task 6.3: List Deal Products
     * GET /api/deals/{id}/products
     */
    public function index($id)
    {
        $deal = CrmDeal::find($id);
        if (!$deal) {
            return response()->json(['success' => false, 'message' => 'Deal not found'], 404);
        }

        $products = CrmDealProduct::where('deal_id', $id)
            ->with(['property', 'bookings' => function($q) {
                $q->latest()->limit(1);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Transform to add readable status and latest booking
        $data = $products->map(function($item) {
            $statusLabel = $item->status instanceof DealsProductStatus ? $item->status->label() : ($item->status ?? 'N/A');
            return [
                'id' => $item->id,
                'property_id' => $item->property_id,
                'property_title' => $item->property->title ?? 'Unknown Property',
                'property_image' => $item->property->title_image ?? null,
                'property_price' => $item->property->price ?? 0,
                'property_address' => $item->property->address ?? '',
                'status' => $item->status,
                'status_label' => $statusLabel,
                'note' => $item->note,
                'latest_booking' => $item->bookings->first(),
                'created_at' => $item->created_at
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Task 6.1: Send Property to Customer
     * POST /api/deals/{id}/products
     */
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:propertys,id',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $deal = CrmDeal::with('customer')->find($id);
        if (!$deal) {
            return response()->json(['success' => false, 'message' => 'Deal not found'], 404);
        }

        // Check if property already added
        $exists = CrmDealProduct::where('deal_id', $id)
            ->where('property_id', $request->property_id)
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Property already added to this deal'], 400);
        }

        DB::beginTransaction();
        try {
            $product = CrmDealProduct::create([
                'deal_id' => $id,
                'property_id' => $request->property_id,
                'status' => DealsProductStatus::SENT_INFO,
                'note' => $request->note,
            ]);

            // Task 6.5: Notification to Customer
            $this->notifyCustomerProperty($deal->customer, $product);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Property sent to customer successfully',
                'data' => $product
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Task 6.2: Update Product Status
     * PATCH /api/deals/products/{id}
     */
    public function update(Request $request, $id)
    {
        // Validate Status Enum values
        // We need to get valid values from Enum
        $validStatuses = array_column(DealsProductStatus::cases(), 'value');
        $validStatusesStr = implode(',', $validStatuses);

        $validator = Validator::make($request->all(), [
            'status' => "required|in:$validStatusesStr",
            'note' => 'nullable|string',
        ]);

        // Custom validation for viewed_failed
        $validator->after(function ($validator) use ($request) {
            if ($request->status === DealsProductStatus::VIEWED_FAILED->value && empty($request->note)) {
                $validator->errors()->add('note', 'Note is required when status is Viewed Failed.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $product = CrmDealProduct::find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Deal Product not found'], 404);
        }

        try {
            $product->status = DealsProductStatus::from($request->status);
            if ($request->has('note')) {
                $product->note = $request->note;
            }
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => $product
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Task 6.5: Notification Logic
     */
    protected function notifyCustomerProperty($customer, $dealProduct)
    {
        if (!$customer || !$customer->telegram_id) {
            return;
        }

        $property = Property::find($dealProduct->property_id);
        if (!$property) return;

        // Construct Caption
        $caption = "🏠 *Gợi ý Bất Động Sản*\n\n";
        $caption .= "📌 *" . ($property->title ?? 'N/A') . "*\n";
        $caption .= "💰 Giá: " . number_format($property->price) . " VNĐ\n";
        $caption .= "📐 Diện tích: " . ($property->area ?? 0) . " m2\n";
        $caption .= "📍 Địa chỉ: " . ($property->address ?? 'N/A') . "\n";
        
        if ($dealProduct->note) {
            $caption .= "📝 Ghi chú: " . $dealProduct->note . "\n";
        }

        // Image
        $imageUrl = $property->title_image ? asset($property->title_image) : null;
        
        // Buttons
        // Link to Property Detail (Public Web or WebApp)
        // Ensure route exists or use a safe fallback
        // $detailUrl = route('bds.show', ['slug' => $property->slug ?? 'id-' . $property->id]);
        $detailUrl = url('/bds/' . ($property->slug ?? 'id-' . $property->id));
        
        // Feedback Button (WebApp)
        // Ideally opens a WebApp form to give feedback
        // $feedbackUrl = route('webapp.feedback', ['product_id' => $dealProduct->id]); // Placeholder route
        
        $options = [
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => '📄 Xem chi tiết', 'url' => $detailUrl],
                        // ['text' => '💬 Phản hồi', 'web_app' => ['url' => $feedbackUrl]] // Add later
                    ]
                ]
            ])
        ];

        // Send Photo if available, otherwise Message
        // NotificationService currently only supports sendMessage (text). 
        // We might need to extend it for sendPhoto, or just send text with link preview.
        // For now, sending text with link.
        
        $this->notificationService->sendToCustomer($customer, $caption, $options);
    }
}
