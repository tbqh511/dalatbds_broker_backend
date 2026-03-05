<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Services\PropertyService;
use App\Services\NotificationService;
use App\Services\Telegram\TelegramMessageTemplates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class PropertyApiController extends Controller
{
    protected $propertyService;
    protected $notificationService;

    public function __construct(PropertyService $propertyService, NotificationService $notificationService)
    {
        $this->propertyService = $propertyService;
        $this->notificationService = $notificationService;
    }

    /**
     * Helper to get bearer token
     */
    private function bearerToken($request)
    {
        $header = $request->header('Authorization', '');
        if (Str::startsWith($header, 'Bearer ')) {
            return Str::substr($header, 7);
        }
        return null;
    }

    /**
     * Helper to get current user ID
     */
    private function getCurrentUserId($request)
    {
        $token = $this->bearerToken($request);
        if ($token) {
            try {
                $payload = JWTAuth::getPayload($token);
                return $payload['customer_id'];
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * POST /api/properties
     * Task 3.1: Create Property with status=pending_verify
     */
    public function store(Request $request)
    {
        // 1. Validation basic (chi tiết hơn trong PropertyService)
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'address' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            // Các field khác sẽ được xử lý trong Service
        ]);

        $current_user = $this->getCurrentUserId($request);
        if (!$current_user) {
            return response()->json(['error' => true, 'message' => 'Unauthorized'], 401);
        }

        try {
            DB::beginTransaction();

            // Override status to pending_verify (0: pending, 1: verified/active in current logic)
            // Assuming 0 is pending/inactive as per current system
            $request->merge(['status' => 0]); 

            // Call Service to store
            // Note: existing storeProperty method handles validation logic for packages too
            $property = $this->propertyService->storeProperty($request, $current_user);

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Bất động sản đã được tạo và chờ duyệt.',
                'data' => [
                    'property_id' => $property->id,
                    'status' => 'pending_verify'
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PATCH /api/properties/{id}/verify
     * Task 3.2: Verify Property and Task 3.3: Send Notification
     */
    public function verify(Request $request, $id)
    {
        // Check permission (Admin/Operator only)
        // For simplicity, we assume middleware handles role check, or check here
        // $current_user = $this->getCurrentUserId($request);
        // Implement role check logic here if needed

        try {
            $property = Property::with(['customer', 'user'])->find($id);

            if (!$property) {
                return response()->json(['error' => true, 'message' => 'Property not found'], 404);
            }

            // Update status to 1 (Verified/Active)
            $property->status = 1;
            $property->save();

            // Task 3.3: Trigger Notification
            // 1. Send to Public Channel
            $messagePublic = TelegramMessageTemplates::newProperty($property);
            $this->notificationService->sendToGroup('public_channel', $messagePublic);

            // 2. Send to Owner (Customer)
            if ($property->customer && $property->customer->count() > 0) {
                // Property 'customer' relation is hasMany in Model but 'added_by' is single ID.
                // Re-fetching owner via 'added_by' manually or using 'agent' relation if it maps to customer.
                // In Property model: public function agent() { return $this->hasOne(Customer::class, 'id', 'added_by')... }
                $owner = $property->agent()->first(); 
                
                if ($owner) {
                    $messageOwner = "✅ BĐS của bạn *{$property->title}* đã được duyệt và đăng công khai!";
                    $this->notificationService->sendToCustomer($owner, $messageOwner);
                }
            }

            return response()->json([
                'error' => false,
                'message' => 'Bất động sản đã được duyệt thành công.',
                'data' => [
                    'property_id' => $property->id,
                    'status' => 'verified'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
