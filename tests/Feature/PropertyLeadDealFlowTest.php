<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\CrmCustomer;
use App\Models\CrmLead;
use App\Models\Property;
use Tymon\JWTAuth\Facades\JWTAuth;

class PropertyLeadDealFlowTest extends TestCase
{
    protected $sale;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a Sale User
        $this->sale = User::factory()->create([
            'telegram_id' => '123456789',
            'status' => 1, // Ensure active
            'type' => 1 // Ensure correct type if needed
        ]);
        
        // Mock Auth instead of using token manually if middleware issues persist in test environment
        // $this->token = JWTAuth::fromUser($this->sale);
        $this->actingAs($this->sale);
    }

    /**
     * Test the flow: Create Property -> Create Lead -> Assign Sale -> Convert to Deal
     */
    public function test_property_lead_deal_flow()
    {
        // 1. Create Property
        $property = Property::create([
            'title' => 'Test Property for Flow',
            'price' => 5000000000,
        ]);
        $this->assertNotNull($property);

        // 2. Create Lead (API: POST /api/leads)
        
        $token = JWTAuth::fromUser($this->sale);
        $headers = ['Authorization' => "Bearer $token"];

        $leadData = [
            'name' => 'Test Customer ' . rand(1000,9999),
            'phone' => '090900' . rand(1000,9999),
            'lead_type' => 'buy',
            'note' => 'Looking for villa',
            'source' => 'web',
            'user_id' => $this->sale->id
        ];

        // Using POST /api/leads with token
        $responseLead = $this->postJson('/api/leads', $leadData, $headers);

        $responseLead->assertStatus(201)
                     ->assertJsonPath('success', true);
        
        $leadId = $responseLead->json('data.id');
        $this->assertNotNull($leadId);

        // 3. Assign Lead to Sale (API: POST /api/leads/{id}/assign)
        $assignData = [
            'sale_id' => $this->sale->id
        ];

        $responseAssign = $this->postJson("/api/leads/{$leadId}/assign", $assignData, $headers);
        
        $responseAssign->assertStatus(200)
                       ->assertJsonPath('success', true)
                       ->assertJsonPath('data.assigned_to', $this->sale->id);

        // 4. Convert to Deal (API: POST /api/deals)
        $lead = CrmLead::find($leadId);
        $customerId = $lead->customer_id;

        $dealData = [
            'lead_id' => $leadId,
            'customer_id' => $customerId,
            'sale_id' => $this->sale->id,
            'amount' => 5000000000,
            'notes' => 'Closing the deal'
        ];

        $responseDeal = $this->postJson('/api/deals', $dealData, $headers);

        $responseDeal->assertStatus(201)
                     ->assertJsonPath('success', true);

        // Verify Lead status updated
        $lead->refresh();
        $this->assertEquals('Converted', $lead->status);
    }
}
