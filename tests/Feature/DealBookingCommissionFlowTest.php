<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\CrmCustomer;
use App\Models\CrmDeal;
use App\Models\Property;
use App\Models\CrmDealProduct;
use App\Enums\DealsProductStatus;
use App\Enums\BookingStatus;
use App\Enums\CommissionStatus;
use Tymon\JWTAuth\Facades\JWTAuth;

class DealBookingCommissionFlowTest extends TestCase
{
    protected $sale;
    protected $token;
    protected $headers;
    protected $deal;
    protected $property;

    protected function setUp(): void
    {
        parent::setUp();
        
        // 1. Create Sale User
        $this->sale = User::factory()->create([
            'telegram_id' => '123456789',
            'status' => 1,
            'type' => 1
        ]);
        
        $this->token = JWTAuth::fromUser($this->sale);
        $this->headers = ['Authorization' => "Bearer {$this->token}"];

        // 2. Setup Data: Customer, Property, Deal
        $customer = CrmCustomer::create(['full_name' => 'Test Customer', 'contact' => '0901234567']);
        $this->property = Property::create(['title' => 'Villa Test', 'price' => 10000000000, 'address' => 'Dalat']);
        
        $this->deal = CrmDeal::create([
            'customer_id' => $customer->id,
            'status' => 'new',
            'amount' => 5000000000,
            'last_updated_by' => $this->sale->id
        ]);
    }

    /**
     * Test full flow: Send Product -> Create Booking -> Update Booking -> Create Commission
     */
    public function test_deal_booking_commission_flow()
    {
        // Step 1: Send Property to Customer (Add to Deal)
        // POST /api/deals/{id}/products
        $productData = [
            'property_id' => $this->property->id,
            'note' => 'Sending villa info'
        ];

        $resProduct = $this->postJson("/api/deals/{$this->deal->id}/products", $productData, $this->headers);
        $resProduct->assertStatus(201)->assertJsonPath('success', true);
        
        $dealProductId = $resProduct->json('data.id');
        $this->assertNotNull($dealProductId);

        // Step 2: Create Booking
        // POST /api/deals/products/{id}/bookings
        $bookingData = [
            'booking_date' => now()->addDay()->format('Y-m-d'),
            'booking_time' => '09:00',
            'note' => 'Viewing appointment'
        ];

        $resBooking = $this->postJson("/api/deals/products/{$dealProductId}/bookings", $bookingData, $this->headers);
        $resBooking->assertStatus(201)->assertJsonPath('success', true);
        
        $bookingId = $resBooking->json('data.id');

        // Step 3: Update Booking Result (Completed Success)
        // PATCH /api/bookings/{id}
        $updateBookingData = [
            'status' => BookingStatus::COMPLETED_SUCCESS->value,
            'customer_feedback' => 'Customer likes it'
        ];

        $resUpdateBooking = $this->patchJson("/api/bookings/{$bookingId}", $updateBookingData, $this->headers);
        $resUpdateBooking->assertStatus(200)
                         ->assertJsonPath('success', true)
                         ->assertJsonPath('data.status', BookingStatus::COMPLETED_SUCCESS->value);

        // Step 4: Create Commission (Deal Closed)
        // POST /api/deals/{id}/commissions
        $commissionData = [
            'sale_id' => $this->sale->id,
            'property_id' => $this->property->id,
            'sale_commission' => 10000000, // 10M
            'notes' => 'Closed deal'
        ];

        $resCommission = $this->postJson("/api/deals/{$this->deal->id}/commissions", $commissionData, $this->headers);
        $resCommission->assertStatus(201)
                      ->assertJsonPath('success', true)
                      ->assertJsonPath('data.status', CommissionStatus::PENDING_DEPOSIT->value);
        
        $commissionId = $resCommission->json('data.id');

        // Step 5: Update Commission Status (Deposited)
        // PATCH /api/commissions/{id}
        $updateCommData = [
            'status' => CommissionStatus::DEPOSITED->value
        ];

        $resUpdateComm = $this->patchJson("/api/commissions/{$commissionId}", $updateCommData, $this->headers);
        $resUpdateComm->assertStatus(200)
                      ->assertJsonPath('success', true)
                      ->assertJsonPath('data.status', CommissionStatus::DEPOSITED->value);
    }
}
