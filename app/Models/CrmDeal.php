<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmDeal extends Model
{
    use HasFactory;

    protected $table = 'crm_deals';

    protected $fillable = [
        'customer_id',
        'notes',
        'status',
        'amount',
        'last_updated_by',
    ];

    // Accessors
    public function getStatusAttribute($value)
    {
        return ucfirst(str_replace('_', ' ', $value));
    }

    public function getAmountAttribute($value)
    {
        return number_format($value, 2);
    }

    // Quan hệ: Một deal thuộc về một khách hàng
    public function customer()
    {
        return $this->belongsTo(CrmCustomer::class, 'customer_id', 'id');
    }

    // Quan hệ: Một deal có nhiều nhân viên được gán
    public function assigneds()
    {
        return $this->hasMany(CrmDealAssigned::class, 'deal_id', 'id');
    }

    // Quan hệ: Một deal có nhiều sản phẩm
    public function products()
    {
        return $this->hasMany(CrmDealProduct::class, 'deal_id', 'id');
    }

    // Quan hệ: Một deal có nhiều khoản hoa hồng
    public function commissions()
    {
        return $this->hasMany(CrmDealCommission::class, 'deal_id', 'id');
    }
}
