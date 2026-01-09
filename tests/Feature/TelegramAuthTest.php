<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TelegramAuthTest extends TestCase
{
    use DatabaseTransactions;

    protected $telegramId = 999999999;
    protected $secret = 'test_secret';

    protected function setUp(): void
    {
        parent::setUp();
        // Mock the environment variable
        putenv('API_LOGIN_SECRET=' . $this->secret);
    }

    /**
     * Test case: Token does not exist (Guest)
     */
    public function test_check_telegram_user_guest()
    {
        // Ensure user does not exist
        Customer::where('telegram_id', $this->telegramId)->delete();

        $response = $this->postJson('/api/check_telegram_user', [
            'telegram_id' => $this->telegramId,
            'secret' => $this->secret
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'guest',
                'message' => 'Người dùng chưa tồn tại. Vui lòng gửi Contact.'
            ]);
    }

    /**
     * Test case: User exists, no token or invalid token -> Generates new token
     */
    public function test_check_telegram_user_generates_new_token_when_missing()
    {
        // Create user without token
        $customer = new Customer();
        $customer->mobile = '0987654321';
        $customer->name = 'Test User';
        $customer->telegram_id = $this->telegramId;
        $customer->isActive = 1;
        $customer->api_token = null; // No token
        $customer->save();

        $response = $this->postJson('/api/check_telegram_user', [
            'telegram_id' => $this->telegramId,
            'secret' => $this->secret
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'authenticated'
            ]);
        
        $token = $response->json('access_token');
        $this->assertNotEmpty($token);

        // Verify token is saved to DB
        $customer->refresh();
        $this->assertEquals($token, $customer->api_token);
    }

    /**
     * Test case: User exists, valid token -> Reuses token
     */
    public function test_check_telegram_user_reuses_valid_token()
    {
        // Create user
        $customer = new Customer();
        $customer->mobile = '0987654321';
        $customer->name = 'Test User';
        $customer->telegram_id = $this->telegramId;
        $customer->isActive = 1;
        $customer->save();

        // Generate a valid token manually
        $token = JWTAuth::fromUser($customer);
        $customer->api_token = $token;
        $customer->save();

        $response = $this->postJson('/api/check_telegram_user', [
            'telegram_id' => $this->telegramId,
            'secret' => $this->secret
        ]);

        $response->assertStatus(200);
        
        // Assert that the returned token is the same as the one we saved
        $this->assertEquals($token, $response->json('access_token'));
    }

    /**
     * Test case: Invalid Secret
     */
    public function test_check_telegram_user_invalid_secret()
    {
        $response = $this->postJson('/api/check_telegram_user', [
            'telegram_id' => $this->telegramId,
            'secret' => 'wrong_secret'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'error' => true,
                'message' => 'Secret key không hợp lệ'
            ]);
    }
}
