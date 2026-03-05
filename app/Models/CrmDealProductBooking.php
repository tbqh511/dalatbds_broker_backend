<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmDealProductBooking extends Model
{
    use HasFactory;

    protected $table = 'crm_deals_product_bookings';

    protected $fillable = [
        'crm_deals_products_id',
        'booking_date',
        'booking_time',
        'status',
        'customer_feedback',
        'internal_note',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'status' => BookingStatus::class,
    ];

    /**
     * Relationship: Belongs to CrmDealProduct
     */
    public function crmDealProduct()
    {
        return $this->belongsTo(CrmDealProduct::class, 'crm_deals_products_id', 'id');
    }
}
