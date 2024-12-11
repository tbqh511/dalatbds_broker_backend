<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmDealAssigned extends Model
{
    use HasFactory;

    protected $table = 'crm_deals_assigned';

    protected $fillable = [
        'deal_id',
        'user_id',
        'note',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    // Relationships
    public function deal()
    {
        return $this->belongsTo(CrmDeal::class, 'deal_id');
    }
}

