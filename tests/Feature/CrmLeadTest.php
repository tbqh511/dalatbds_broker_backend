<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Customer;
use App\Models\CrmLead;

class CrmLeadTest extends TestCase
{
    // use RefreshDatabase; // Be careful with this on existing DB

    public function test_user_can_view_leads()
    {
        $customer = Customer::factory()->create();
        
        $response = $this->actingAs($customer, 'webapp')
                         ->get(route('webapp.leads'));

        $response->assertStatus(200);
        $response->assertViewIs('frontend_dashboard_leads');
    }

    public function test_user_can_create_lead()
    {
        $customer = Customer::factory()->create();

        $leadData = [
            'name' => 'Test Customer',
            'phone' => '0901234567',
            'lead_type' => 'buy',
            'status' => 'new',
            'price_min' => 1000000,
            'price_max' => 5000000,
            'note' => 'Test Note'
        ];

        $response = $this->actingAs($customer, 'webapp')
                         ->post(route('webapp.leads.store'), $leadData);

        $response->assertRedirect(route('webapp.leads'));
        
        $this->assertDatabaseHas('crm_leads', [
            'user_id' => $customer->id,
            'lead_type' => 'buy',
            'status' => 'new'
        ]);
    }
}
