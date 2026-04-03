<?php

namespace App\Events;

use App\Models\InAppNotification;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewInAppNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $notification;
    public int $customerId;
    public int $unreadCount;

    public function __construct(InAppNotification $notification, int $unreadCount)
    {
        $this->notification = $notification->toActivityArray();
        $this->customerId = $notification->customer_id;
        $this->unreadCount = $unreadCount;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('customer.' . $this->customerId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'notification.new';
    }

    public function broadcastWith(): array
    {
        return [
            'notification' => $this->notification,
            'unread_count' => $this->unreadCount,
        ];
    }
}
