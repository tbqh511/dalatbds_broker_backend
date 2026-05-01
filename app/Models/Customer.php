<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Customer extends Authenticatable implements JWTSubject
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'firebase_id',
        'mobile',
        'telegram_id',
        'telegram_bot_started',
        'profile',
        'address',
        'bio',
        'zalo',
        'facebook_link',
        'years_experience',
        'work_area',
        'specialization',
        'fcm_id',
        'logintype',
        'isActive',
        'role',
        'referral_code',
        'referred_by',
        'notification_settings',
    ];

    protected $hidden = [
        'api_token',
        'remember_token',
    ];

    protected $casts = [
        'notification_settings' => 'array',
        'telegram_bot_started'  => 'boolean',
    ];

    public const DEFAULT_NOTIFICATION_SETTINGS = [
        'master'   => true,
        'lead'     => ['assigned' => true, 'followup' => true, 'channels' => ['telegram', 'in_app']],
        'deal'     => ['status' => true, 'feedback' => true, 'stuck' => true, 'channels' => ['telegram', 'in_app']],
        'booking'  => ['day_before' => true, 'hour_before' => true, 'result' => true, 'channels' => ['telegram', 'in_app']],
        'commission' => ['approved' => true, 'status' => true, 'channels' => ['telegram', 'in_app', 'zalo']],
        'property' => ['status' => true, 'interest' => true, 'expiry' => false, 'channels' => ['telegram', 'in_app']],
        'referral' => ['new_signup' => true, 'channels' => ['telegram', 'in_app']],
        'market'   => ['news' => true, 'ai_suggest' => true, 'promotions' => false],
        'quiet_hours' => ['enabled' => false, 'start' => '22:00', 'end' => '07:00'],
    ];

    public function getMergedNotifSettings(): array
    {
        $saved = $this->notification_settings ?? [];
        return array_replace_recursive(self::DEFAULT_NOTIFICATION_SETTINGS, $saved);
    }

    public function inAppNotifications()
    {
        return $this->hasMany(InAppNotification::class);
    }

    protected $appends = [
        'customertotalpost'
    ];
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'auth_model' => 'customer',
            'customer_id' => $this->id
        ];
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (empty($customer->referral_code)) {
                $customer->referral_code = static::generateReferralCode();
            }
        });
    }

    public static function generateReferralCode(): string
    {
        do {
            $code = 'DLBDS-' . strtoupper(substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(6))), 0, 6));
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }

    public function referrer()
    {
        return $this->belongsTo(Customer::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(Customer::class, 'referred_by');
    }

    public function user_purchased_package()
    {
        return  $this->morphMany(UserPurchasedPackage::class, 'modal');
    }

    public function getCustomerTotalPostAttribute()
    {
        //return Property::where('added_by', $this->id)->get()->count();
        return $this->property()->count();
    }
    public function favourite()
    {
        return $this->hasMany(Favourite::class, 'user_id');
    }
    public function property()
    {
        return $this->hasMany(Property::class, 'added_by');
    }
    public function getProfileAttribute($image)
    {
        return $image != '' ? url('') . config('global.IMG_PATH') . config('global.USER_IMG_PATH') . $image : url('') . config('global.IMG_PATH') . '/default-user-img.png';
    }
    public function usertokens()
    {
        return $this->hasMany(Usertokens::class, 'customer_id');
    }
    // Danh sách role hợp lệ của webapp
    public const VALID_ROLES = ['customer', 'guest', 'broker', 'bds_admin', 'sale', 'sale_admin', 'admin'];

    // Hierarchy: mỗi role kế thừa quyền của các role thấp hơn (đồng bộ với webapp-v2.js)
    public static function roleHierarchy(): array
    {
        return [
            'guest'      => ['guest'],
            'customer'   => ['guest', 'customer'],
            'broker'     => ['guest', 'customer', 'broker'],
            'bds_admin'  => ['guest', 'customer', 'broker', 'bds_admin'],
            'sale'       => ['guest', 'customer', 'broker', 'sale'],
            'sale_admin' => ['guest', 'customer', 'broker', 'sale', 'sale_admin'],
            'admin'      => ['guest', 'customer', 'broker', 'bds_admin', 'sale', 'sale_admin', 'admin'],
        ];
    }

    /**
     * Tính role hiệu lực theo logic nghiệp vụ:
     * - Role đặc quyền (admin/bds_admin/sale_admin) → dùng role đó, KHÔNG yêu cầu SĐT
     * - Không có SĐT → guest (với các role thông thường)
     * - Có SĐT, role hợp lệ (broker/sale/sale_admin/bds_admin/admin) → dùng role đó
     * - Có SĐT, role DB là 'guest'/null/unknown → broker (mặc định)
     */
    public function getEffectiveRole(): string
    {
        // Admin-level roles bypass phone requirement.
        // Accounts created manually (without bot flow) still have full access.
        if (in_array($this->role, ['admin', 'bds_admin', 'sale_admin'])) {
            return $this->role;
        }

        if (empty($this->mobile)) {
            return 'guest';
        }

        // Flutter App users mặc định lưu role='customer' hoặc NULL.
        // Map thành 'broker' để họ có thể dùng WebApp cơ bản ngay lập tức
        // mà không cần sửa code Flutter.
        // Admin vẫn thấy họ trong tab "Chờ duyệt" để nâng cấp role chính thức.
        if (empty($this->role) || $this->role === 'customer') {
            return 'broker';
        }

        if (in_array($this->role, self::VALID_ROLES)) {
            return $this->role;
        }

        // Fallback: có SĐT nhưng role không hợp lệ → mặc định broker
        return 'broker';
    }

    /**
     * Format SĐT để hiển thị: 84918963878 → +84 918 963 878
     */
    public function getFormattedMobileAttribute(): string
    {
        $phone = $this->mobile ?? '';
        if (preg_match('/^84(\d{3})(\d{3})(\d{3})$/', $phone, $m)) {
            return "+84 {$m[1]} {$m[2]} {$m[3]}";
        }
        // Fallback cho format cũ 0xxx
        if (preg_match('/^0(\d{3})(\d{3})(\d{3})$/', $phone, $m)) {
            return "0{$m[1]} {$m[2]} {$m[3]}";
        }
        return $phone;
    }

    // Kiểm tra role với hierarchy (admin có quyền của mọi role thấp hơn)
    public function hasRole(string ...$roles): bool
    {
        $hierarchy = self::roleHierarchy();
        $allowed = $hierarchy[$this->getEffectiveRole()] ?? ['guest'];
        return count(array_intersect($allowed, $roles)) > 0;
    }

    public function isSale(): bool
    {
        return $this->hasRole('sale', 'sale_admin');
    }

    public function isSaleAdmin(): bool
    {
        return $this->hasRole('sale_admin');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    //HuyTBQ: get list locationsWards
    public function getAgentWardsAttribute()
    {
        // Lấy danh sách các ward mà đại lý quản lý
        $wardCodes = $this->property()->distinct()->pluck('ward_code');

        // Lấy danh sách các LocationWard tương ứng với các ward_code
        $wards = LocationsWard::whereIn('code', $wardCodes)->get();
        return $wards;
    }
}