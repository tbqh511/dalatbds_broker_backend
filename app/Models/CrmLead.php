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
}

