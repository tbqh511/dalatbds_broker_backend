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
    ];

    public const DEFAULT_NOTIFICATION_SETTINGS = [
        'master'   => true,
        'lead'     => ['assigned' => true, 'followup' => true, 'channels' => ['telegram', 'in_app']],
        'deal'     => ['status' => true, 'feedback' => true, 'stuck' => true, 'channels' => ['telegram', 'in_app']],
        'booking'  => ['day_before' => true, 'hour_before' => true, 'result' => true, 'channels' => ['telegram', 'in_app']],
        'commission' => ['approved' => true, 'status' => true, 'channels' => ['telegram', 'in_app', 'zalo']],
        'property' => ['status' => true, 'interest' => true, 'expiry' => false, 'channels' => ['telegram', 'in_app']],
        'market'   => ['news' => true, 'ai_suggest' => true, 'promotions' => false],
        'quiet_hours' => ['enabled' => true, 'start' => '22:00', 'end' => '07:00'],
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
        return $image != '' ? url('') . config('global.IMG_PATH') . config('global.USER_IMG_PATH') . $image : url('') . config('global.IMG_PATH') . config('global.USER_IMG_PATH').'1693209486.1303.png';
    }
    public function usertokens()
    {
        return $this->hasMany(Usertokens::class, 'customer_id');
    }
    // Danh sách role hợp lệ của webapp
    public const VALID_ROLES = ['guest', 'broker', 'bds_admin', 'sale', 'sale_admin', 'admin'];

    // Hierarchy: mỗi role kế thừa quyền của các role thấp hơn (đồng bộ với webapp-v2.js)
    public static function roleHierarchy(): array
    {
        return [
            'guest'      => ['guest'],
            'broker'     => ['guest', 'broker'],
            'bds_admin'  => ['guest', 'broker', 'bds_admin'],
            'sale'       => ['guest', 'broker', 'sale'],
            'sale_admin' => ['guest', 'broker', 'sale', 'sale_admin'],
            'admin'      => ['guest', 'broker', 'bds_admin', 'sale', 'sale_admin', 'admin'],
        ];
    }

    /**
     * Tính role hiệu lực theo logic nghiệp vụ:
     * - Không có SĐT → guest (dù role DB là gì)
     * - Có SĐT, role được cấp (broker/sale/sale_admin/bds_admin/admin) → dùng role đó
     * - Có SĐT, role DB là 'customer'/null/unknown → broker (mặc định)
     */
    public function getEffectiveRole(): string
    {
        if (empty($this->mobile)) {
            return 'guest';
        }

        if (in_array($this->role, self::VALID_ROLES)) {
            return $this->role;
        }

        return 'broker';
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