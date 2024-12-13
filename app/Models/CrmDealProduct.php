<?php

namespace App\Models;

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

    // Accessors
    public function getStatusAttribute($value)
    {
        return ucfirst(str_replace('_', ' ', $value));
    }

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
}
