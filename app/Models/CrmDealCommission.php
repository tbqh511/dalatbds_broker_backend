<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmDealCommission extends Model
{
    use HasFactory;

    protected $table = 'crm_deals_commissions';

    protected $fillable = [
        'deal_id',
        'sale_id',
        'property_id',
        'sale_commission',
        'app_commission',
        'lead_commission',
        'owner_commission',
        'notes',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    // Accessors
    public function getSaleCommissionAttribute($value)
    {
        return number_format($value, 2);
    }

    public function getAppCommissionAttribute($value)
    {
        return number_format($value, 2);
    }

    // Quan hệ: Thuộc về một deal
    public function deal()
    {
        return $this->belongsTo(CrmDeal::class, 'deal_id', 'id');
    }

    // Quan hệ: Một lead thuộc user app
    public function sale()
    {
        return $this->belongsTo(Customer::class, 'sale_id', 'id');
    }

    // Relationship: Thuộc về một property
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }
}
