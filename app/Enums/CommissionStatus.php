<?php

namespace App\Enums;

enum CommissionStatus: string
{
    case PENDING_DEPOSIT = 'pending_deposit';
    case DEPOSITED = 'deposited';
    case NOTARIZING = 'notarizing';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING_DEPOSIT => 'Chờ cọc',
            self::DEPOSITED => 'Đã cọc',
            self::NOTARIZING => 'Công chứng',
            self::COMPLETED => 'Hoàn tất',
            self::CANCELLED => 'Hủy',
        };
    }
}
