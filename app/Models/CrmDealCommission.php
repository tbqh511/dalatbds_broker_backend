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
}
