<?php

namespace App\Enums;

enum DealsProductStatus: string
{
    case SENT_INFO = 'sent_info';
    case SENT_LOCATION = 'sent_location';
    case SENT_LEGAL = 'sent_legal';
    case CUSTOMER_FEEDBACK = 'customer_feedback';
    case BOOKING_CREATED = 'booking_created';
    case VIEWED_SUCCESS = 'viewed_success';
    case VIEWED_FAILED = 'viewed_failed';
    case NEGOTIATING = 'negotiating';
    case WAITING_FINANCE = 'waiting_finance';

    public function label(): string
    {
        return match ($this) {
            self::SENT_INFO => 'Gửi thông tin',
            self::SENT_LOCATION => 'Gửi vị trí',
            self::SENT_LEGAL => 'Gửi pháp lý',
            self::CUSTOMER_FEEDBACK => 'Khách phản hồi',
            self::BOOKING_CREATED => 'Tạo lịch xem',
            self::VIEWED_SUCCESS => 'Xem thành công',
            self::VIEWED_FAILED => 'Xem thất bại',
            self::NEGOTIATING => 'Đang thương lượng',
            self::WAITING_FINANCE => 'Chờ tài chính',
        };
    }
}
