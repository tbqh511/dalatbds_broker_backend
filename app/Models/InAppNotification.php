<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InAppNotification extends Model
{
    protected $table = 'in_app_notifications';

    protected $fillable = [
        'customer_id',
        'type',
        'category',
        'title',
        'body',
        'data',
        'notifiable_type',
        'notifiable_id',
        'actor_id',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    // ── Relationships ──

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function actor()
    {
        return $this->belongsTo(Customer::class, 'actor_id');
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    // ── Scopes ──

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeForCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // ── Accessors ──

    public function getIsUnreadAttribute(): bool
    {
        return is_null($this->read_at);
    }

    public function getTimeAgoAttribute(): string
    {
        Carbon::setLocale('vi');
        return $this->created_at->diffForHumans();
    }

    // ── Serialization ──

    public function toActivityArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'category' => $this->category,
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data,
            'is_unread' => $this->is_unread,
            'time_ago' => $this->time_ago,
            'created_at' => $this->created_at->toIso8601String(),
            'type_config' => \App\Services\InAppNotificationService::typeConfig($this->type),
        ];
    }
}
