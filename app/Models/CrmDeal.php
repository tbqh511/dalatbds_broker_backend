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
}
