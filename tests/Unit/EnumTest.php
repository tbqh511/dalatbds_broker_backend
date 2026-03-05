<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Enums\DealsProductStatus;
use App\Enums\BookingStatus;
use App\Enums\CommissionStatus;

class EnumTest extends TestCase
{
    /**
     * Test DealsProductStatus labels
     */
    public function test_deals_product_status_labels()
    {
        $this->assertEquals('Gửi thông tin', DealsProductStatus::SENT_INFO->label());
        $this->assertEquals('Gửi vị trí', DealsProductStatus::SENT_LOCATION->label());
        $this->assertEquals('Xem thành công', DealsProductStatus::VIEWED_SUCCESS->label());
        $this->assertEquals('Đang thương lượng', DealsProductStatus::NEGOTIATING->label());
    }

    /**
     * Test BookingStatus labels
     */
    public function test_booking_status_labels()
    {
        $this->assertEquals('Đã lên lịch', BookingStatus::SCHEDULED->label());
        $this->assertEquals('Đã xem - Ưng ý', BookingStatus::COMPLETED_SUCCESS->label());
        $this->assertEquals('Hủy lịch', BookingStatus::CANCELLED->label());
    }

    /**
     * Test CommissionStatus labels
     */
    public function test_commission_status_labels()
    {
        $this->assertEquals('Chờ cọc', CommissionStatus::PENDING_DEPOSIT->label());
        $this->assertEquals('Đã cọc', CommissionStatus::DEPOSITED->label());
        $this->assertEquals('Hoàn tất', CommissionStatus::COMPLETED->label());
    }
}
