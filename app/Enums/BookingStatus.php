<?php

namespace App\Enums;

enum BookingStatus: string
{
    case SCHEDULED = 'scheduled';
    case COMPLETED_SUCCESS = 'completed_success';
    case COMPLETED_NEGOTIATING = 'completed_negotiating';
    case COMPLETED_FAILED = 'completed_failed';
    case RESCHEDULED = 'rescheduled';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::SCHEDULED => 'Đã lên lịch',
            self::COMPLETED_SUCCESS => 'Đã xem - Ưng ý',
            self::COMPLETED_NEGOTIATING => 'Đã xem - Đang thương lượng',
            self::COMPLETED_FAILED => 'Đã xem - Không ưng',
            self::RESCHEDULED => 'Dời lịch',
            self::CANCELLED => 'Hủy lịch',
        };
    }
}
