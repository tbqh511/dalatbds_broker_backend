<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmCustomer extends Model
{
    use HasFactory;

    protected $table = 'crm_customers';

    protected $fillable = [
        'full_name',
        'gender',
        'age',
        'about_customer',
        'contact',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    // Accessors
    public function getFullNameAttribute($value)
    {
        return ucwords($value);
    }

    public function getContactAttribute($value)
    {
        return $value ? "$value" : 'N/A';
    }


    public function leads()
    {
        return $this->hasMany(CrmLead::class, 'customer_id', 'id');
    }


    public function deals()
    {
        return $this->hasMany(CrmDeal::class, 'customer_id', 'id');
    }
}
