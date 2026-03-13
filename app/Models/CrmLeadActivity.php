<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmLeadActivity extends Model
{
    use HasFactory;

    protected $table = 'crm_lead_activities';

    protected $fillable = [
        'lead_id',
        'actor_id',
        'type',
        'content',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // Types: call, note, assignment, status_change

    public function lead()
    {
        return $this->belongsTo(CrmLead::class, 'lead_id');
    }

    public function actor()
    {
        return $this->belongsTo(Customer::class, 'actor_id');
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'call'          => 'Gọi điện',
            'note'          => 'Ghi chú',
            'assignment'    => 'Phân công',
            'status_change' => 'Cập nhật trạng thái',
            default         => $this->type,
        };
    }

    public function getTypeIcon(): string
    {
        return match ($this->type) {
            'call'          => 'fa-phone',
            'note'          => 'fa-sticky-note',
            'assignment'    => 'fa-user-plus',
            'status_change' => 'fa-exchange-alt',
            default         => 'fa-circle',
        };
    }
}
