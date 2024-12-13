<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmLead extends Model
{
    use HasFactory;

    protected $table = 'crm_leads';

    protected $fillable = [
        'user_id',
        'customer_id',
        'source_note',
        'lead_type',
        'categories',
        'wards',
        'demand_rate_min',
        'demand_rate_max',
        'note',
        'status',
    ];

    protected $casts = [
        'categories' => 'array',
        'wards' => 'array',
    ];

    // Accessors
    public function getLeadTypeAttribute($value)
    {
        return $value === 'buy' ? 'Buy' : 'Rent';
    }

    public function getStatusAttribute($value)
    {
        return ucfirst(str_replace('-', ' ', $value));
    }

   // Quan hệ: Một lead thuộc về người dùng
    public function user()
    {
        return $this->belongsTo(Customer::class, 'user_id', 'id')
                    ->select(['id', 'name', 'email', 'profile']);
    }

    // Quan hệ: Một lead thuộc về một khách hàng
    public function customer()
    {
        return $this->belongsTo(CrmCustomer::class, 'customer_id', 'id');
    }

    // Quan hệ: Một lead có thể được chuyển đổi thành một deal
    public function deal()
    {
        return $this->hasOne(CrmDeal::class, 'lead_id', 'id');
    }
}

