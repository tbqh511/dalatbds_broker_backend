<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\CrmHost;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;

class HostManagementTest extends TestCase
{
    use WithFaker;

    protected $customer;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure we have a customer
        $this->customer = Customer::first();
        if (!$this->customer) {
            $this->customer = Customer::factory()->create();
        }

        // Ensure we have a category
        $this->category = Category::first();
        if (!$this->category) {
            $this->category = Category::create([
                'category' => 'Test Category',
                'status' => '1'
            ]);
        }
    }
    
    protected function tearDown(): void
    {
        // Clean up hosts created during tests
        CrmHost::where('contact', '84999888777')->delete();
        parent::tearDown();
    }

    public function test_api_check_host_phone()
    {
        // Clean up first
        CrmHost::where('contact', '84999888777')->delete();

        // Create a host
        $host = new CrmHost();
        $host->name = 'Test Host API';
        $host->contact = '84999888777';
        $host->gender = '1';
        $host->save();

        $this->actingAs($this->customer, 'webapp');

        $response = $this->get('/webapp/check-host-phone?phone=0999888777');
        
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Test Host API']);
        $response->assertJsonFragment(['contact' => '84999888777']);
    }

    public function test_submit_listing_deduplicates_host()
    {
        // Clean up first
        CrmHost::where('contact', '84999888777')->delete();

        $this->actingAs($this->customer, 'webapp');

        // 1. Submit first time
        $data1 = [
            'type' => $this->category->id,
            'transactionType' => 'sale',
            'price' => 1000,
            'area' => 100,
            'description' => 'Test Description',
            'contact' => json_encode([
                'name' => 'Host One',
                'phone' => '0999888777',
                'gender' => '1'
            ])
        ];

        $response1 = $this->post(route('webapp.submit_listing'), $data1);
        if ($response1->status() !== 200) {
            dump($response1->json());
        }
        $response1->assertStatus(200);
        
        $countAfterFirst = CrmHost::where('contact', '84999888777')->count();
        $this->assertEquals(1, $countAfterFirst, 'Should have 1 host after first submission');

        sleep(1); // Ensure unique slug timestamp

        // 2. Submit second time with same phone but different name
        $data2 = [
            'type' => $this->category->id,
            'transactionType' => 'sale',
            'price' => 2000,
            'area' => 200,
            'description' => 'Test Description 2',
            'contact' => json_encode([
                'name' => 'Host Two', // Name changes
                'phone' => '0999888777', // Phone same
                'gender' => '1'
            ])
        ];

        $response2 = $this->post(route('webapp.submit_listing'), $data2);
        if ($response2->status() !== 200) {
            dump($response2->json());
        }
        $response2->assertStatus(200);

        $countAfterSecond = CrmHost::where('contact', '84999888777')->count();
        $this->assertEquals(1, $countAfterSecond, 'Should still have 1 host after second submission');
        
        // Check if name was updated
        $host = CrmHost::where('contact', '84999888777')->first();
        $this->assertEquals('Host Two', $host->name, 'Host name should be updated');
    }

    public function test_submit_listing_handles_notes()
    {
        // Clean up first
        CrmHost::where('contact', '84999888777')->delete();
        $this->actingAs($this->customer, 'webapp');
        
        sleep(1); // Avoid duplicate slug collision from previous tests

        // 1. Submit with note first time
        $data1 = [
            'type' => $this->category->id,
            'transactionType' => 'sale',
            'price' => 1000,
            'area' => 100,
            'description' => 'Note Test 1',
            'contact' => json_encode([
                'name' => 'Host Note Test',
                'phone' => '0999888777',
                'gender' => '1',
                'note' => 'First note'
            ])
        ];

        $response1 = $this->post(route('webapp.submit_listing'), $data1);
        if ($response1->status() !== 200) {
            dump($response1->json());
        }
        $response1->assertStatus(200);
        
        $host = CrmHost::where('contact', '84999888777')->first();
        $this->assertEquals('First note', $host->about);

        sleep(1);

        // 2. Submit second time with NEW note
        $data2 = [
            'type' => $this->category->id,
            'transactionType' => 'sale',
            'price' => 2000,
            'area' => 200,
            'description' => 'Note Test 2',
            'contact' => json_encode([
                'name' => 'Host Note Test',
                'phone' => '0999888777',
                'gender' => '1',
                'note' => 'Second note'
            ])
        ];

        $this->post(route('webapp.submit_listing'), $data2)->assertStatus(200);
        
        $host->refresh();
        $this->assertStringContainsString('First note', $host->about);
        $this->assertStringContainsString('Second note', $host->about);
        
        sleep(1); // Avoid duplicate slug collision

        // 3. Submit third time with SAME note (should not duplicate)
        $this->post(route('webapp.submit_listing'), $data2)->assertStatus(200);
        
        $host->refresh();
        // Count occurrences of "Second note"
        $count = substr_count($host->about, 'Second note');
        $this->assertEquals(1, $count, 'Should not duplicate same note');
    }
}
