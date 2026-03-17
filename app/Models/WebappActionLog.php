<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebappActionLog extends Model
{
    protected $table = 'webapp_action_logs';

    protected $fillable = [
        'subject_type',
        'subject_id',
        'subject_title',
        'actor_id',
        'action',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // Actions: call, share, edit, view, create, delete

    public function actor()
    {
        return $this->belongsTo(Customer::class, 'actor_id');
    }

    public function getActionLabel(): string
    {
        return match ($this->action) {
            'call'   => 'Gọi điện',
            'share'  => 'Chia sẻ',
            'edit'   => 'Chỉnh sửa',
            'view'   => 'Xem chi tiết',
            'create' => 'Tạo mới',
            'delete' => 'Xóa',
            default  => $this->action,
        };
    }

    public function getActionColor(): string
    {
        return match ($this->action) {
            'call'   => '#059669',  // green
            'share'  => '#3270FC',  // primary blue
            'edit'   => '#d97706',  // amber
            'view'   => '#6b7280',  // gray
            'create' => '#7c3aed',  // purple
            'delete' => '#dc2626',  // red
            default  => '#6b7280',
        };
    }

    public function getSubjectLabel(): string
    {
        return match ($this->subject_type) {
            'property' => 'BĐS',
            'lead'     => 'Lead',
            'deal'     => 'Deal',
            default    => $this->subject_type,
        };
    }
}
