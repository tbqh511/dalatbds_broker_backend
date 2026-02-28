<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Package;
use App\Models\UserPurchasedPackage;
use App\Models\Usertokens;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class UserService
{
    /**
     * Handle user signup or login via Firebase/Mobile.
     * Corresponds to user_signup in ApiController.
     */
    public function signup(array $data)
    {
        $type = $data['type'] ?? '';
        $firebase_id = $data['firebase_id'];
        $mobile = $data['mobile'] ?? null;

        $user = Customer::where(function ($query) use ($firebase_id, $mobile) {
            $query->where('firebase_id', $firebase_id);
            if (!is_null($mobile)) {
                $query->orWhere('mobile', $mobile);
            }
        })
        ->where('logintype', $type)
        ->first();

        if (!$user) {
            // Register new user
            $user = new Customer();
            $user->name = $data['name'] ?? '';
            $user->email = $data['email'] ?? '';
            $user->mobile = $data['mobile'] ?? '';
            $user->fcm_id = $data['fcm_id'] ?? '';
            $user->logintype = $data['type'] ?? '';
            $user->address = $data['address'] ?? '';
            $user->firebase_id = $data['firebase_id'] ?? '';
            $user->isActive = '1';

            // Handle profile image
            if (isset($data['profile_file']) && $data['profile_file']) {
                $profile = $data['profile_file'];
                $destinationPath = public_path('images') . config('global.USER_IMG_PATH');
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                $profile->move($destinationPath, $imageName);
                $user->profile = $imageName;
            } else {
                $user->profile = '';
            }

            $user->save();

            // Assign default package
            $start_date = Carbon::now();
            $package = Package::find(1);
            if ($package && $package->status == 1) {
                $user_package = new UserPurchasedPackage();
                $user_package->modal()->associate($user);
                $user_package->package_id = 1;
                $user_package->start_date = $start_date;
                $user_package->end_date = Carbon::now()->addDays($package->duration);
                $user_package->save();

                $user->subscription = 1;
                $user->update();
            }

            $message = 'User Register Successfully';
        } else {
            $message = 'Login Successfully';
        }

        // Generate Token
        try {
            $token = JWTAuth::fromUser($user);
            if (!$token) {
                throw new Exception('Login credentials are invalid.');
            }
            $user->api_token = $token;
            $user->update();
        } catch (JWTException $e) {
            throw new Exception('Could not create token.');
        }

        return [
            'user' => $user,
            'token' => $token,
            'message' => $message
        ];
    }

    /**
     * Handle server-to-server login.
     * Corresponds to login in ApiController.
     */
    public function login(array $data)
    {
        // 1. Normalize phone
        $rawPhone = $data['phone'];
        $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);

        $phoneVariants = [];
        if (substr($cleanPhone, 0, 1) === '0') {
            $phone84 = '84' . substr($cleanPhone, 1);
            $phoneVariants[] = $phone84;
            $phoneVariants[] = $cleanPhone; // 0xxx
        } elseif (substr($cleanPhone, 0, 2) === '84') {
            $phone0 = '0' . substr($cleanPhone, 2);
            $phoneVariants[] = $cleanPhone; // 84...
            $phoneVariants[] = $phone0; // 0...
        } else {
            $phoneVariants[] = $cleanPhone;
            $phoneVariants[] = '0' . $cleanPhone;
            $phoneVariants[] = '84' . $cleanPhone;
        }

        // remove duplicates
        $phoneVariants = array_values(array_unique($phoneVariants));

        // 2. Find user
        $query = Customer::query();
        $query->where(function ($q) use ($phoneVariants) {
            $q->whereIn('mobile', $phoneVariants);
            if (Schema::hasColumn('customers', 'phone')) {
                $q->orWhereIn('phone', $phoneVariants);
            }
        });

        $customer = $query->first();

        if (!$customer) {
            // Auto-register new customer
            try {
                $customer = new Customer();
                $customer->mobile = $cleanPhone;
                if (Schema::hasColumn('customers', 'phone')) {
                    $customer->phone = $cleanPhone;
                }
                
                $customer->name = $data['first_name'] ?? 'Khách hàng Mới';
                $customer->telegram_id = $data['telegram_id'] ?? null;
                $customer->isActive = 1;
                $customer->logintype = 1; // Mobile login
                $customer->notification = 1;
                $customer->email = $data['email'] ?? '';
                $customer->firebase_id = $data['firebase_id'] ?? '';
                $customer->address = '';
                $customer->fcm_id = '';
                
                $customer->save();
                
                Log::info("Auto-registered new customer via API Login: ID {$customer->id}, Phone: {$customer->mobile}");
                
            } catch (\Exception $e) {
                Log::error("Auto-registration failed: " . $e->getMessage());
                throw new Exception('Tự động đăng ký thất bại. Vui lòng thử lại sau.');
            }
        } else {
             Log::info("Customer login: ID {$customer->id}, Phone: {$customer->mobile}");
        }

        // 3. Check status
        if (isset($customer->isActive) && $customer->isActive == 0) {
            throw new Exception('Tài khoản đã bị khóa.');
        }

        // 4. Update info if provided
        if (isset($data['telegram_id'])) {
            $customer->telegram_id = $data['telegram_id'];
            if (isset($data['first_name']) && empty($customer->name)) {
                $customer->name = $data['first_name'];
            }
            $customer->save();
        }

        // 5. Generate Token
        try {
            $token = JWTAuth::fromUser($customer);
            if (!$token) {
                throw new Exception('Tạo token thất bại');
            }
            $customer->api_token = $token;
            $customer->save();
        } catch (JWTException $e) {
            throw new Exception('Tạo token thất bại');
        }

        return [
            'user' => $customer,
            'token' => $token,
            'message' => 'Đăng nhập thành công'
        ];
    }

    /**
     * Update user profile.
     * Corresponds to update_profile in ApiController.
     */
    public function updateProfile(Customer $customer, array $data)
    {
        if (isset($data['name'])) {
            $customer->name = $data['name'] ?: '';
        }
        if (isset($data['email'])) {
            $customer->email = $data['email'] ?: '';
        }
        if (isset($data['mobile'])) {
            $customer->mobile = $data['mobile'] ?: '';
        }

        if (isset($data['fcm_id'])) {
            $token_exist = Usertokens::where('fcm_id', $data['fcm_id'])->get();
            if (!count($token_exist)) {
                $user_token = new Usertokens();
                $user_token->customer_id = $customer->id;
                $user_token->fcm_id = $data['fcm_id'] ?: '';
                $user_token->api_token = '';
                $user_token->save();
            }
            $customer->fcm_id = $data['fcm_id'] ?: '';
        }
        if (isset($data['address'])) {
            $customer->address = $data['address'] ?: '';
        }

        if (isset($data['firebase_id'])) {
            $customer->firebase_id = $data['firebase_id'] ?: '';
        }
        if (isset($data['notification'])) {
            $customer->notification = $data['notification'];
        }

        // Handle profile image
        if (isset($data['profile_file']) && $data['profile_file']) {
            $destinationPath = public_path('images') . config('global.USER_IMG_PATH');
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            
            $old_image = $customer->profile;
            $profile = $data['profile_file'];
            $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
            $profile->move($destinationPath, $imageName);
            $customer->profile = $imageName;

            if ($old_image != '') {
                if (file_exists(public_path('images') . config('global.USER_IMG_PATH') . $old_image)) {
                    unlink(public_path('images') . config('global.USER_IMG_PATH') . $old_image);
                }
            }
        }

        $customer->save();

        return $customer;
    }

    /**
     * Get user profile.
     */
    public function getProfile(Customer $customer)
    {
        return $customer;
    }
}
