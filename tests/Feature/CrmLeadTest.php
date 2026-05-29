<?php

namespace Tests\Feature;

use App\Models\CrmCustomer;
use App\Models\CrmLead;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CrmLeadTest extends TestCase
{
    use WithFaker;

    // use RefreshDatabase; // Be careful with this on existing DB
    // Lưu ý: bộ test chạy trên DB thật (dalatbds_local), không có RefreshDatabase.
    // Vì vậy dùng SĐT ngẫu nhiên và dọn dẹp bản ghi đã tạo sau mỗi test.

    /** @var array<int> CrmCustomer ids đã tạo trong test, để dọn dẹp */
    private array $createdCustomerIds = [];

    protected function tearDown(): void
    {
        if (! empty($this->createdCustomerIds)) {
            CrmLead::whereIn('customer_id', $this->createdCustomerIds)->delete();
            CrmCustomer::whereIn('id', $this->createdCustomerIds)->delete();
        }

        parent::tearDown();
    }

    /**
     * Tạo SĐT VN ngẫu nhiên hợp lệ (10 số, bắt đầu 09) để tránh đụng dữ liệu thật.
     */
    private function randomVnPhone(): string
    {
        return '09'.str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
    }

    /**
     * Chuẩn hóa SĐT giống storeCustomer (0xxxxxxxxx -> 84xxxxxxxxx) để query DB.
     */
    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);

        return substr($digits, 0, 1) === '0' ? '84'.substr($digits, 1) : $digits;
    }

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
            'note' => 'Test Note',
        ];

        $response = $this->actingAs($customer, 'webapp')
            ->post(route('webapp.leads.store'), $leadData);

        $response->assertRedirect(route('webapp.leads'));

        $this->assertDatabaseHas('crm_leads', [
            'user_id' => $customer->id,
            'lead_type' => 'buy',
            'status' => 'new',
        ]);
    }

    /**
     * Hai broker khác nhau lưu cùng một SĐT phải tạo 2 bản ghi CrmCustomer độc lập;
     * tên khách của broker A KHÔNG bị broker B ghi đè (đây là lỗi gốc cần khắc phục).
     */
    public function test_two_brokers_same_phone_do_not_overwrite()
    {
        $brokerA = Customer::factory()->create();
        $brokerB = Customer::factory()->create();

        $phone = $this->randomVnPhone();
        $normalized = $this->normalizePhone($phone);

        // Broker A lưu khách "Nguyen Van A"
        $resA = $this->actingAs($brokerA, 'webapp')->postJson(route('webapp.store_customer'), [
            'name' => 'Nguyen Van A',
            'phone' => $phone,
            'lead_type' => 'buy',
        ]);
        $resA->assertOk()->assertJson(['success' => true]);

        // Broker B lưu cùng SĐT, tên khác "Tran Thi B"
        $resB = $this->actingAs($brokerB, 'webapp')->postJson(route('webapp.store_customer'), [
            'name' => 'Tran Thi B',
            'phone' => $phone,
            'lead_type' => 'rent',
        ]);
        $resB->assertOk()->assertJson(['success' => true]);

        $custA = CrmCustomer::where('user_id', $brokerA->id)->where('contact', $normalized)->first();
        $custB = CrmCustomer::where('user_id', $brokerB->id)->where('contact', $normalized)->first();
        $this->trackForCleanup([$custA, $custB]);

        // Hai bản ghi riêng biệt, mỗi broker một bản
        $this->assertNotNull($custA, 'Broker A phải có bản ghi CrmCustomer riêng');
        $this->assertNotNull($custB, 'Broker B phải có bản ghi CrmCustomer riêng');
        $this->assertNotEquals($custA->id, $custB->id, 'Hai broker phải có customer_id khác nhau');

        // Tên của Broker A KHÔNG bị ghi đè bởi Broker B
        // (getFullNameAttribute dùng ucwords nên so sánh với dạng ucwords)
        $this->assertEquals('Nguyen Van A', $custA->full_name);
        $this->assertEquals('Tran Thi B', $custB->full_name);

        // Mỗi broker có lead riêng trỏ về customer của chính mình
        $this->assertDatabaseHas('crm_leads', ['user_id' => $brokerA->id, 'customer_id' => $custA->id]);
        $this->assertDatabaseHas('crm_leads', ['user_id' => $brokerB->id, 'customer_id' => $custB->id]);
    }

    /**
     * Cùng broker lưu lại SĐT cũ với tên sửa: chỉ 1 bản ghi CrmCustomer cho broker đó,
     * full_name được cập nhật (hành vi mong muốn — sửa typo), và tạo lead thứ 2.
     */
    public function test_same_broker_same_phone_updates_name_not_duplicate()
    {
        $broker = Customer::factory()->create();
        $phone = $this->randomVnPhone();
        $normalized = $this->normalizePhone($phone);

        $this->actingAs($broker, 'webapp')->postJson(route('webapp.store_customer'), [
            'name' => 'Le Van Cu',
            'phone' => $phone,
            'lead_type' => 'buy',
        ])->assertOk();

        $this->actingAs($broker, 'webapp')->postJson(route('webapp.store_customer'), [
            'name' => 'Le Van Sua',
            'phone' => $phone,
            'lead_type' => 'rent',
        ])->assertOk();

        $customers = CrmCustomer::where('user_id', $broker->id)->where('contact', $normalized)->get();
        $this->trackForCleanup($customers->all());

        // Chỉ một bản ghi khách cho broker này
        $this->assertCount(1, $customers, 'Cùng broker + cùng SĐT không được tạo trùng');
        $this->assertEquals('Le Van Sua', $customers->first()->full_name);

        // Nhưng vẫn tạo 2 lead riêng biệt
        $this->assertEquals(2, CrmLead::where('user_id', $broker->id)
            ->where('customer_id', $customers->first()->id)->count());
    }

    /**
     * Lead tạo từ add-customer có user_id = broker và customer thuộc về broker đó.
     */
    public function test_store_customer_creates_lead_with_broker_user_id()
    {
        $broker = Customer::factory()->create();
        $phone = $this->randomVnPhone();
        $normalized = $this->normalizePhone($phone);

        $this->actingAs($broker, 'webapp')->postJson(route('webapp.store_customer'), [
            'name' => 'Pham Thi D',
            'phone' => $phone,
            'lead_type' => 'buy',
        ])->assertOk()->assertJson(['success' => true]);

        $customer = CrmCustomer::where('user_id', $broker->id)->where('contact', $normalized)->first();
        $this->trackForCleanup([$customer]);

        $this->assertNotNull($customer);
        $this->assertEquals($broker->id, $customer->user_id);
        $this->assertDatabaseHas('crm_leads', [
            'user_id' => $broker->id,
            'customer_id' => $customer->id,
            'lead_type' => 'buy',
            'status' => 'new',
        ]);
    }

    /**
     * @param  array<\App\Models\CrmCustomer|null>  $customers
     */
    private function trackForCleanup(array $customers): void
    {
        foreach ($customers as $c) {
            if ($c) {
                $this->createdCustomerIds[] = $c->id;
            }
        }
    }
}
