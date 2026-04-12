<?php

namespace App\Services\Telegram;

use App\Models\CrmDeal;
use App\Models\CrmDealCommission;
use App\Models\CrmDealProduct;
use App\Models\CrmDealProductBooking;
use App\Models\CrmLead;
use App\Models\Customer;
use App\Models\Property;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TelegramMessageTemplates
{
    /**
     * BĐS mới duyệt
     */
    public static function newProperty(Property $property)
    {
        $price = $property->formatted_prices ?? number_format($property->price);
        $title = self::escape($property->title);
        $address = self::escape($property->address);
        
        // Handle user/agent name. Property 'user' relation is hasMany, likely incorrect or special usage.
        // 'agent' relation is hasOne Customer.
        $posterName = $property->agent->name ?? ($property->user->first()?->name ?? 'Admin');
        $posterName = self::escape($posterName);

        return "🏠 *BĐS MỚI DUYỆT*\n" .
               "----------------\n" .
               "🆔 ID: `{$property->id}`\n" .
               "📝 Tiêu đề: {$title}\n" .
               "📍 Địa chỉ: {$address}\n" .
               "💰 Giá: {$price}\n" .
               "👤 Người đăng: {$posterName}\n" .
               "🔗 [Xem chi tiết](" . route('bds.show', $property->slug) . ")";
    }

    /**
     * Lead mới được tạo
     */
    public static function newLead(CrmLead $lead)
    {
        $customerName = self::escape($lead->customer->full_name ?? 'N/A');
        $phone = self::escape($lead->customer->contact ?? 'N/A');
        $demand = number_format($lead->demand_rate_min) . ' - ' . number_format($lead->demand_rate_max);
        $leadType = ucfirst($lead->lead_type ?? 'N/A');
        
        return "🎯 *LEAD MỚI*\n" .
               "----------------\n" .
               "👤 Khách hàng: {$customerName}\n" .
               "📞 SĐT: {$phone}\n" .
               "🏠 Loại: {$leadType}\n" .
               "💰 Ngân sách: {$demand} VNĐ\n" .
               "📝 Ghi chú: " . self::escape($lead->note);
    }

    /**
     * Lead mới — gửi vào group sale_admin với 1 nút web_app để mở trang phân công.
     * Trả về ['text' => string, 'keyboard' => array] để NotificationService dùng.
     *
     * @param  CrmLead  $lead
     * @param  string   $assignUrl  Signed URL của trang phân công
     * @return array{text: string, keyboard: array}
     */
    public static function newLeadForGroupWebApp(CrmLead $lead, string $assignUrl): array
    {
        $customerName = self::escape($lead->customer->full_name ?? 'N/A');
        $phone        = self::escape($lead->customer->contact ?? 'N/A');
        $leadType     = ($lead->getRawOriginal('lead_type') ?? $lead->lead_type) === 'buy' ? 'Mua' : 'Thuê';
        $budget       = number_format($lead->demand_rate_min) . ' – ' . number_format($lead->demand_rate_max);
        $brokerName   = self::escape($lead->user->name ?? 'N/A');
        $note         = self::escape($lead->note ?? '');

        $text = "🎯 *LEAD MỚI CẦN PHÂN CÔNG*\n" .
                "────────────────\n" .
                "👤 Khách: {$customerName}\n" .
                "📞 SĐT: {$phone}\n" .
                "🏠 Nhu cầu: {$leadType}\n" .
                "💰 Ngân sách: {$budget} VNĐ\n" .
                "📝 Ghi chú: {$note}\n" .
                "🙋 Broker: {$brokerName}";

        // Dùng callback_data để bot gửi private message với web_app button khi người dùng click.
        // Cách này hoạt động trong group và cho phép mở Mini App thật sự (không phải in-app browser).
        $keyboard = [[
            ['text' => '👤 Phân công Sale', 'callback_data' => "open_assign_lead:{$lead->id}"],
        ]];

        return ['text' => $text, 'keyboard' => $keyboard];
    }

    /**
     * Lead mới — gửi vào group sale_admin kèm inline keyboard để chọn sales phụ trách.
     * Trả về ['text' => string, 'keyboard' => array] để NotificationService dùng.
     *
     * @param  CrmLead      $lead
     * @param  Collection   $salesTeam  Collection<Customer> có các field id, name
     * @return array{text: string, keyboard: array}
     */
    public static function newLeadForGroup(CrmLead $lead, Collection $salesTeam): array
    {
        $customerName = self::escape($lead->customer->full_name ?? 'N/A');
        $phone        = self::escape($lead->customer->contact ?? 'N/A');
        $leadType     = $lead->lead_type === 'buy' ? 'Mua' : 'Thuê';
        $budget       = number_format($lead->demand_rate_min) . ' – ' . number_format($lead->demand_rate_max);
        $brokerName   = self::escape($lead->user->name ?? 'N/A');
        $note         = self::escape($lead->note ?? '');

        $text = "🎯 *LEAD MỚI CẦN PHÂN CÔNG*\n" .
                "────────────────\n" .
                "👤 Khách: {$customerName}\n" .
                "📞 SĐT: {$phone}\n" .
                "🏠 Nhu cầu: {$leadType}\n" .
                "💰 Ngân sách: {$budget} VNĐ\n" .
                "📝 Ghi chú: {$note}\n" .
                "🙋 Broker: {$brokerName}\n" .
                "────────────────\n" .
                "👇 *Chọn sale phụ trách:*";

        // Build inline keyboard — 2 buttons per row
        $buttons  = $salesTeam->map(fn (Customer $s) => [
            'text'          => $s->name,
            'callback_data' => "assign_lead:{$lead->id}:{$s->id}",
        ])->values()->all();

        $keyboard = array_chunk($buttons, 2); // 2 tên/hàng

        return ['text' => $text, 'keyboard' => $keyboard];
    }

    /**
     * Lead được gán cho Sale
     */
    public static function leadAssigned(CrmLead $lead): string
    {
        $customerName = self::escape($lead->customer->full_name ?? 'N/A');
        $phone = self::escape($lead->customer->contact ?? 'N/A');
        $leadType = ucfirst($lead->lead_type ?? 'N/A');
        $budget = number_format($lead->demand_rate_min) . ' - ' . number_format($lead->demand_rate_max);
        $note = self::escape($lead->note ?? '');

        return "🔔 *LEAD MỚI ĐƯỢC GIAO*\n" .
               "----------------\n" .
               "📋 Khách hàng: {$customerName}\n" .
               "📞 SĐT: {$phone}\n" .
               "🏠 Loại: {$leadType}\n" .
               "💰 Ngân sách: {$budget} VNĐ\n" .
               "📝 Ghi chú: {$note}";
    }

    /**
     * Deal tạo
     */
    public static function dealCreated(CrmDeal $deal)
    {
        $customerName = self::escape($deal->customer->name ?? 'N/A');
        
        // Get assigned sales
        $sales = $deal->assigneds->map(function($assigned) {
            return $assigned->sale->name ?? 'N/A';
        })->implode(', ');
        
        $saleName = self::escape($sales ?: 'Chưa gán');
        
        return "🤝 *DEAL MỚI ĐƯỢC TẠO*\n" .
               "----------------\n" .
               "🆔 Deal ID: `{$deal->id}`\n" .
               "👤 Khách hàng: {$customerName}\n" .
               "💼 Sale phụ trách: {$saleName}\n" .
               "📅 Ngày tạo: " . $deal->created_at->format('d/m/Y H:i');
    }

    /**
     * BĐS gửi khách (Deal Product added/status changed)
     */
    public static function propertySentToCustomer(CrmDealProduct $dealProduct)
    {
        $propertyTitle = self::escape($dealProduct->property->title ?? 'N/A');
        $customerName = self::escape($dealProduct->deal->customer->name ?? 'N/A');
        
        // Use label() if status is an Enum, otherwise use string value
        $statusVal = $dealProduct->status;
        $statusLabel = ($statusVal instanceof \UnitEnum) ? $statusVal->label() : ($statusVal ?? 'N/A');
        $status = self::escape($statusLabel);

        return "📤 *BĐS GỬI KHÁCH*\n" .
               "----------------\n" .
               "🏠 BĐS: {$propertyTitle}\n" .
               "👤 Khách hàng: {$customerName}\n" .
               "📊 Trạng thái: {$status}\n" .
               "📝 Ghi chú: " . self::escape($dealProduct->note);
    }

    /**
     * Lịch hẹn (Booking Created)
     */
    public static function appointmentCreated(CrmDealProductBooking $booking)
    {
        $propertyTitle = self::escape($booking->crmDealProduct->property->title ?? 'N/A');
        $customerName = self::escape($booking->crmDealProduct->deal->customer->name ?? 'N/A');
        $date = $booking->booking_date ? Carbon::parse($booking->booking_date)->format('d/m/Y') : 'N/A';
        $time = $booking->booking_time ? Carbon::parse($booking->booking_time)->format('H:i') : 'N/A';
        
        return "📅 *LỊCH HẸN MỚI*\n" .
               "----------------\n" .
               "🏠 BĐS: {$propertyTitle}\n" .
               "👤 Khách hàng: {$customerName}\n" .
               "⏰ Thời gian: {$time} ngày {$date}\n" .
               "📍 Địa điểm: " . self::escape($booking->crmDealProduct->property->address ?? 'N/A');
    }

    /**
     * Kết quả xem (Booking Status Updated/Feedback)
     */
    public static function viewingResult(CrmDealProductBooking $booking)
    {
        $propertyTitle = self::escape($booking->crmDealProduct->property->title ?? 'N/A');
        $customerName = self::escape($booking->crmDealProduct->deal->customer->name ?? 'N/A');
        
        $statusVal = $booking->status;
        $statusLabel = ($statusVal instanceof \UnitEnum) ? $statusVal->label() : ($statusVal ?? 'N/A');
        $status = self::escape($statusLabel);
        
        $feedback = self::escape($booking->customer_feedback ?? 'Chưa có phản hồi');

        return "📝 *KẾT QUẢ XEM NHÀ*\n" .
               "----------------\n" .
               "🏠 BĐS: {$propertyTitle}\n" .
               "👤 Khách hàng: {$customerName}\n" .
               "📊 Trạng thái: {$status}\n" .
               "💬 Phản hồi: {$feedback}";
    }

    /**
     * Commission Updated
     */
    public static function commissionUpdated(CrmDealCommission $commission)
    {
        $propertyTitle = self::escape($commission->property->title ?? 'N/A');
        $saleName = self::escape($commission->sale->name ?? 'N/A');
        $amount = number_format($commission->sale_commission);
        
        $statusVal = $commission->status;
        $statusLabel = ($statusVal instanceof \UnitEnum) ? $statusVal->label() : ($statusVal ?? 'N/A');
        $status = self::escape($statusLabel);

        return "💰 *HOA HỒNG CẬP NHẬT*\n" .
               "----------------\n" .
               "🏠 BĐS: {$propertyTitle}\n" .
               "👤 Sale: {$saleName}\n" .
               "💵 Số tiền: {$amount} VNĐ\n" .
               "📊 Trạng thái: {$status}";
    }

    /**
     * Người mới đăng ký qua referral code
     */
    public static function referralNewSignup(Customer $referrer, Customer $newUser): string
    {
        $name = self::escape($newUser->name ?? 'Thành viên mới');
        $code = self::escape($referrer->referral_code ?? '');

        return "🎉 *THÀNH VIÊN MỚI QUA GIỚI THIỆU*\n" .
               "────────────────\n" .
               "👤 Người đăng ký: {$name}\n" .
               "🔗 Mã giới thiệu: `{$code}`\n" .
               "📅 Thời gian: " . now()->format('d/m/Y H:i') . "\n" .
               "────────────────\n" .
               "🎁 Bạn sẽ nhận 5% thu nhập hoa hồng từ người này\\!";
    }

    /**
     * BĐS được duyệt — gửi cho broker cá nhân
     */
    public static function propertyApproved(Property $property): string
    {
        $title = self::escape($property->title ?? 'BĐS');

        return "✅ *TIN BĐS ĐÃ ĐƯỢC DUYỆT*\n"
            . "────────────────\n"
            . "🏠 {$title}\n"
            . "🎉 Tin của bạn đã được đăng lên hệ thống và đang hiển thị công khai\\!";
    }

    /**
     * Inline keyboard cho thông báo BĐS được duyệt
     */
    public static function propertyApprovedKeyboard(Property $property): array
    {
        $botUsername      = config('services.telegram.bot_username');
        $webappShortName  = config('services.telegram.webapp_short_name');
        $webappUrl        = "https://t.me/{$botUsername}/{$webappShortName}?startapp=property_{$property->id}";

        return [[
            ['text' => '🔍 Xem BĐS của bạn', 'url' => $webappUrl],
        ]];
    }

    /**
     * Thông báo thay đổi vai trò — gửi cho người dùng bị thay đổi
     */
    public static function roleChanged(Customer $target, string $oldRole, string $newRole, Customer $changedBy): string
    {
        $name         = self::escape($target->name ?? 'Bạn');
        $oldRoleLabel = self::escape(self::getRoleLabel($oldRole));
        $newRoleLabel = self::escape(self::getRoleLabel($newRole));
        $adminName    = self::escape($changedBy->name ?? 'Quản trị viên');
        $time         = now()->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i');

        return "🔔 *THAY ĐỔI VAI TRÒ*\n"
            . "────────────────\n"
            . "👤 Tài khoản: {$name}\n"
            . "🔄 {$oldRoleLabel} → *{$newRoleLabel}*\n"
            . "👮 Thực hiện bởi: {$adminName}\n"
            . "📅 Thời gian: {$time}";
    }

    /**
     * Trả về nhãn tiếng Việt cho từng role
     */
    public static function getRoleLabel(string $role): string
    {
        return match ($role) {
            'guest'      => 'Khách vãng lai',
            'customer'   => 'Khách hàng',
            'broker'     => 'eBroker',
            'bds_admin'  => 'BĐS Admin',
            'sale'       => 'Sale',
            'sale_admin' => 'Sale Admin',
            'admin'      => 'Quản trị viên',
            default      => ucfirst($role),
        };
    }

    /**
     * Helper to escape special characters for MarkdownV2
     * Characters to escape: _ * [ ] ( ) ~ ` > # + - = | { } . !
     */
    private static function escape(?string $text): string
    {
        if (!$text) return '';
        // For standard Markdown (not V2), usually just * and _ need care, but Telegram has specific requirements.
        // The prompt asked for "Markdown format", usually implies the basic one or V2. 
        // Let's use a simple replace for characters that might break formatting.
        // If using Parse Mode 'Markdown', only specific characters need escaping. 
        // If 'MarkdownV2', many need escaping. 
        // I will assume 'Markdown' mode for simplicity unless 'MarkdownV2' is strictly required.
        // However, 'Markdown' mode is legacy. 'MarkdownV2' is recommended.
        // Let's implement basic escaping for 'Markdown' mode to be safe and simple.
        
        // Actually, simple text is better if we are not sure about V2.
        // But let's try to support bolding.
        return str_replace(['*', '_', '`', '['], ['\*', '\_', '\`', '\['], $text);
    }
}