<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class WebappListingTest extends TestCase
{
    // use RefreshDatabase; // Use with caution on existing DB

    protected $customer;

    protected function setUp(): void
    {
        parent::setUp();
        // Create or get a test customer
        // Assuming we have factories. If not, we might need to manually create.
        // For existing DB, let's use a mock user or create one transactionally.
        // Since we shouldn't wipe the DB, let's try to mock Auth.
    }

    /** @test */
    public function unauthenticated_user_cannot_delete_listing()
    {
        $response = $this->deleteJson(route('webapp.listings.destroy', ['id' => 999]));
        
        // Should be 401 or redirect to login depending on middleware.
        // Since the route is in 'telegram.webapp' group, it might redirect or return 401.
        // Our controller check returns 401 explicitly if Auth::guard('webapp')->user() is null.
        
        // Note: Middleware might intercept first.
        // If middleware redirects, status is 302.
        
        $response->assertStatus(302); // Likely redirects to login
    }

    /** @test */
    public function authenticated_user_can_delete_own_listing()
    {
        // Mock Auth
        $customer = Customer::factory()->create();
        $this->actingAs($customer, 'webapp');

        $property = Property::factory()->create([
            'added_by' => $customer->id,
            'title' => 'Test Property Delete'
        ]);

        $response = $this->deleteJson(route('webapp.listings.destroy', ['id' => $property->id]));

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('propertys', ['id' => $property->id]);
        
        // Clean up
        $customer->delete();
    }

    /** @test */
    public function authenticated_user_cannot_delete_others_listing()
    {
        $owner = Customer::factory()->create();
        $other = Customer::factory()->create();
        
        $property = Property::factory()->create([
            'added_by' => $owner->id,
            'title' => 'Owner Property'
        ]);

        $this->actingAs($other, 'webapp');

        $response = $this->deleteJson(route('webapp.listings.destroy', ['id' => $property->id]));

        $response->assertStatus(404); // Or 403, our controller returns 404 with specific message
        
        $this->assertDatabaseHas('propertys', ['id' => $property->id]);

        // Clean up
        $property->delete();
        $owner->delete();
        $other->delete();
    }

    /** @test */
    public function toggle_status_works_correctly()
    {
        $customer = Customer::factory()->create();
        $this->actingAs($customer, 'webapp');

        $property = Property::factory()->create([
            'added_by' => $customer->id,
            'status' => 1 // Active
        ]);

        // Toggle 1 -> 2 (Hide)
        $response = $this->patchJson(route('webapp.listings.toggle', ['id' => $property->id]));
        
        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'status' => 2]);
        
        $this->assertEquals(2, $property->fresh()->status);

        // Toggle 2 -> 1 (Show)
        $response = $this->patchJson(route('webapp.listings.toggle', ['id' => $property->id]));
        
        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'status' => 1]);

        $this->assertEquals(1, $property->fresh()->status);

        // Clean up
        $property->delete();
        $customer->delete();
    }
}
