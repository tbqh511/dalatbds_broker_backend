<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\InAppNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class InAppNotificationService
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Create an in-app notification if the customer has in_app enabled for this category/key.
     */
    public function notify(Customer $recipient, string $type, string $category, string $settingsKey, array $payload): ?InAppNotification
    {
        if (!$this->notificationService->shouldNotify($recipient, $category, $settingsKey, 'in_app')) {
            return null;
        }

        $notification = InAppNotification::create([
            'customer_id'     => $recipient->id,
            'type'            => $type,
            'category'        => $category,
            'title'           => $payload['title'],
            'body'            => $payload['body'] ?? null,
            'data'            => $payload['data'] ?? null,
            'notifiable_type' => $payload['notifiable_type'] ?? null,
            'notifiable_id'   => $payload['notifiable_id'] ?? null,
            'actor_id'        => $payload['actor_id'] ?? null,
        ]);

        // Broadcast real-time via WebSocket
        $unreadCount = $this->unreadCount($recipient->id);
        event(new \App\Events\NewInAppNotification($notification, $unreadCount));

        return $notification;
    }

    /**
     * Create a notification directly, bypassing shouldNotify() checks.
     * Use for system-level notifications (e.g. admin alerts) that must always be delivered.
     */
    public function notifyDirect(Customer $recipient, string $type, string $category, array $payload): InAppNotification
    {
        $notification = InAppNotification::create([
            'customer_id'     => $recipient->id,
            'type'            => $type,
            'category'        => $category,
            'title'           => $payload['title'],
            'body'            => $payload['body'] ?? null,
            'data'            => $payload['data'] ?? null,
            'notifiable_type' => $payload['notifiable_type'] ?? null,
            'notifiable_id'   => $payload['notifiable_id'] ?? null,
            'actor_id'        => $payload['actor_id'] ?? null,
        ]);

        $unreadCount = $this->unreadCount($recipient->id);
        event(new \App\Events\NewInAppNotification($notification, $unreadCount));

        return $notification;
    }

    /**
     * Mark all property_pending notifications for a given property as read (handled by another admin).
     */
    public function markPropertyHandled(int $propertyId, int $handledById): int
    {
        return InAppNotification::where('type', 'property_pending')
            ->whereJsonContains('data->property_id', $propertyId)
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
                'data'    => \Illuminate\Support\Facades\DB::raw(
                    "JSON_SET(data, '$.handled_by_id', {$handledById})"
                ),
            ]);
    }

    /**
     * Update the existing property_submitted notification when property is approved/rejected.
     * Instead of creating a new record, update the old one to avoid spam.
     */
    public function updatePropertyNotification(int $propertyId, int $brokerId, array $updateData): bool
    {
        $notification = InAppNotification::where('customer_id', $brokerId)
            ->where('type', 'property_submitted')
            ->whereJsonContains('data->property_id', $propertyId)
            ->latest()
            ->first();

        if (!$notification) {
            return false;
        }

        $notification->update([
            'title'   => $updateData['title'],
            'body'    => $updateData['body'] ?? $notification->body,
            'type'    => $updateData['type'] ?? $notification->type,
            'read_at' => null, // Reset read status to show as unread again
            'data'    => array_merge($notification->data ?? [], $updateData['data'] ?? []),
        ]);

        // Broadcast lại để client nhận real-time update
        $unreadCount = $this->unreadCount($brokerId);
        event(new \App\Events\NewInAppNotification($notification->fresh(), $unreadCount));

        return true;
    }

    /**
     * Notify multiple customers.
     */
    public function notifyMany(Collection $recipients, string $type, string $category, string $settingsKey, array $payload): int
    {
        $count = 0;
        foreach ($recipients as $recipient) {
            if ($this->notify($recipient, $type, $category, $settingsKey, $payload)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(int $notificationId, int $customerId): bool
    {
        return InAppNotification::where('id', $notificationId)
            ->where('customer_id', $customerId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]) > 0;
    }

    /**
     * Mark all notifications as read, optionally filtered by category.
     */
    public function markAllAsRead(int $customerId, ?string $category = null): int
    {
        $query = InAppNotification::where('customer_id', $customerId)->whereNull('read_at');

        if ($category) {
            $query->where('category', $category);
        }

        return $query->update(['read_at' => now()]);
    }

    /**
     * Get unread count for a customer.
     */
    public function unreadCount(int $customerId): int
    {
        try {
            return InAppNotification::where('customer_id', $customerId)->whereNull('read_at')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get paginated notifications with optional category filter.
     * $categories: empty = all, one item = single filter, multiple = whereIn filter
     */
    public function getNotifications(int $customerId, array $categories = [], int $perPage = 15, bool $unreadOnly = false): LengthAwarePaginator
    {
        $query = InAppNotification::where('customer_id', $customerId)
            ->orderByDesc('created_at');

        if (!empty($categories)) {
            $query->whereIn('category', $categories);
        }

        if ($unreadOnly) {
            $query->whereNull('read_at');
        }

        return $query->paginate($perPage);
    }

    /**
     * UI config for each notification type (icon, colors, actions).
     */
    public static function typeConfig(string $type): array
    {
        $configs = [
            'lead_assigned' => [
                'icon_bg' => 'var(--danger-light)',
                'icon'    => 'target',
            ],
            'lead_followup' => [
                'icon_bg' => 'var(--warning-light)',
                'icon'    => 'bell',
            ],
            'lead_created' => [
                'icon_bg' => 'var(--primary-light)',
                'icon'    => 'user-plus',
            ],
            'booking_reminder' => [
                'icon_bg' => 'var(--primary-light)',
                'icon'    => 'calendar',
            ],
            'booking_result' => [
                'icon_bg' => 'var(--primary-light)',
                'icon'    => 'clipboard',
            ],
            'booking_changed' => [
                'icon_bg' => 'var(--warning-light)',
                'icon'    => 'refresh',
            ],
            'property_submitted' => [
                'icon_bg' => 'var(--primary-light)',
                'icon'    => 'clock',
            ],
            'property_approved' => [
                'icon_bg' => 'var(--success-light)',
                'icon'    => 'check',
            ],
            'property_rejected' => [
                'icon_bg' => 'var(--danger-light)',
                'icon'    => 'x-circle',
            ],
            'property_pending' => [
                'icon_bg' => 'var(--warning-light)',
                'icon'    => 'clock',
            ],
            'commission_status' => [
                'icon_bg' => 'var(--success-light)',
                'icon'    => 'dollar',
            ],
            'commission_completed' => [
                'icon_bg' => 'var(--success-light)',
                'icon'    => 'check',
            ],
            'deal_created' => [
                'icon_bg' => 'var(--primary-light)',
                'icon'    => 'handshake',
            ],
            'deal_stuck' => [
                'icon_bg' => 'var(--warning-light)',
                'icon'    => 'alert-triangle',
            ],
            'deal_status' => [
                'icon_bg' => 'var(--primary-light)',
                'icon'    => 'activity',
            ],
            'referral_new_signup' => [
                'icon_bg' => 'var(--purple-light, rgba(124,58,237,0.12))',
                'icon'    => 'gift',
            ],
            'welcome_ebroker' => [
                'icon_bg' => 'var(--success-light)',
                'icon'    => 'star',
            ],
        ];

        return $configs[$type] ?? ['icon_bg' => 'var(--primary-light)', 'icon' => 'bell'];
    }
}
