<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Customer;
use App\Models\Property;

class TelegramWebAppListingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_webapp_listings_returns_properties_for_authenticated_customer()
    {
        // Create a customer
        $customer = Customer::factory()->create();

        // Create properties for this customer
        for ($i = 0; $i < 5; $i++) {
            Property::create([
                'category_id' => '1',
                'package_id' => null,
                'title' => 'Property ' . $i,
                'description' => 'Desc ' . $i,
                'address' => 'Address ' . $i,
                'client_address' => 'Client address ' . $i,
                'propery_type' => 0,
                'price' => '1000000',
                'post_type' => null,
                'city' => 'City',
                'country' => 'VN',
                'state' => null,
                'title_image' => '',
                'threeD_image' => '',
                'video_link' => '',
                'latitude' => '0',
                'longitude' => '0',
                'added_by' => $customer->id,
                'status' => 1,
                'total_click' => 0,
            ]);
        }

        // Act as the customer using webapp guard
        $response = $this->actingAs($customer, 'webapp')->get('/webapp/listings');

        $response->assertStatus(200);
        $response->assertViewHas('properties');
        $this->assertEquals(5, $response->viewData('properties')->total());
    }
}
