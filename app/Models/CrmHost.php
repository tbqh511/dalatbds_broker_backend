<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmHost extends Model
{
    protected $table = 'crm_hosts';

    protected $fillable = [
        'name',
        'gender',
        'contact',
        'age',
        'company',
        'about',
    ];

    // Relationship: Một CrmHost có nhiều Property
    public function properties()
    {
        return $this->hasMany(Property::class, 'host_id', 'id');
    }
}
