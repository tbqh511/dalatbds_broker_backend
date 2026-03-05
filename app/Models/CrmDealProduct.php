<?php

namespace App\Models;

use App\Enums\DealsProductStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmDealProduct extends Model
{
    use HasFactory;

    protected $table = 'crm_deals_products';

    protected $fillable = [
        'deal_id',
        'property_id',
        'note',
        'status',
        'reason_dont_like',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'status' => DealsProductStatus::class,
    ];

    // Quan hệ: Thuộc về một deal
    public function deal()
    {
        return $this->belongsTo(CrmDeal::class, 'deal_id', 'id');
    }

    // Relationship: Thuộc về một property
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    // Relationship: Có nhiều bookings
    public function bookings()
    {
        return $this->hasMany(CrmDealProductBooking::class, 'crm_deals_products_id', 'id');
    }
}
