<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use Illuminate\Support\Facades\Schema;

class AutoRegisterLoginTest extends TestCase
{
    protected $secret;

    protected function setUp(): void
    {
        parent::setUp();
        // Lấy secret từ env hoặc sử dụng giá trị mặc định đã biết
        $this->secret = env('API_LOGIN_SECRET', 'Q2hhbmdlbWUAQDIwMjMuQGxhdHByb2plY3Q=');
    }

    /**
     * Test 1: Đăng nhập với số điện thoại MỚI -> Hệ thống tự động đăng ký
     */
    public function test_login_auto_registers_new_customer()
    {
        // Tạo số điện thoại ngẫu nhiên để đảm bảo chưa tồn tại
        $phone = '849' . rand(10000000, 99999999);
        
        // Xóa sạch nếu lỡ có tồn tại (để test case độc lập)
        Customer::where('mobile', $phone)->delete();

        // Gửi request login
        $response = $this->postJson('/api/login', [
            'phone' => $phone,
            'secret' => $this->secret,
            'first_name' => 'Test Auto Register',
            'telegram_id' => '123456789'
        ]);

        // Kiểm tra HTTP Status và cấu trúc JSON trả về
        $response->assertStatus(200)
                 ->assertJson([
                     'error' => false,
                     'message' => 'Đăng nhập thành công'
                 ]);

        // Kiểm tra các trường quan trọng trong response
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'data' => [
                'id',
                'mobile',
                'name',
                'telegram_id',
                'api_token'
            ]
        ]);

        // Kiểm tra dữ liệu đã được lưu vào Database chưa
        $this->assertDatabaseHas('customers', [
            'mobile' => $phone,
            'name' => 'Test Auto Register',
            'telegram_id' => '123456789',
            'isActive' => 1
        ]);

        // Dọn dẹp dữ liệu sau khi test
        Customer::where('mobile', $phone)->delete();
    }

    /**
     * Test 2: Đăng nhập với khách hàng ĐÃ TỒN TẠI -> Đăng nhập bình thường
     */
    public function test_login_existing_customer()
    {
        // Chuẩn bị dữ liệu mẫu
        $phone = '849' . rand(10000000, 99999999);
        
        $customer = new Customer();
        $customer->mobile = $phone;
        $customer->name = 'Existing User';
        $customer->isActive = 1;
        $customer->logintype = 1;
        if (Schema::hasColumn('customers', 'phone')) {
            $customer->phone = $phone;
        }
        $customer->save();

        // Gửi request login
        $response = $this->postJson('/api/login', [
            'phone' => $phone,
            'secret' => $this->secret
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'error' => false,
                     'message' => 'Đăng nhập thành công'
                 ]);
                 
        // Kiểm tra ID trả về khớp với ID đã tạo
        $this->assertEquals($customer->id, $response->json('data.id'));

        // Dọn dẹp
        $customer->delete();
    }

    /**
     * Test 3: Đăng nhập với Secret sai -> Trả về lỗi 401
     */
    public function test_login_with_invalid_secret()
    {
        $response = $this->postJson('/api/login', [
            'phone' => '84912345678',
            'secret' => 'wrong_secret_key'
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'error' => true,
                     'message' => 'Secret key không hợp lệ'
                 ]);
    }
}
