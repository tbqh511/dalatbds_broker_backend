<?php

namespace App\Http\Controllers;

use App\Models\CrmLead;
use App\Models\CrmLeadActivity;
use App\Models\Customer;
use App\Services\NotificationService;
use App\Services\Telegram\TelegramMessageTemplates;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class TelegramBotController extends Controller
{
    public function __construct(protected NotificationService $notificationService) {}

    /**
     * Telegram webhook entry point.
     * POST /api/telegram/webhook
     */
    public function webhook(Request $request)
    {
        // Verify Telegram secret token header
        $secret = Config::get('services.telegram.webhook_secret');
        if ($secret && $request->header('X-Telegram-Bot-Api-Secret-Token') !== $secret) {
            return response()->json(['ok' => false], 403);
        }

        $body = $request->all();

        if (isset($body['callback_query'])) {
            $this->handleCallbackQuery($body['callback_query']);
        }

        if (isset($body['message'])) {
            $this->handleMessage($body['message']);
        }

        return response()->json(['ok' => true]);
    }

    protected function handleCallbackQuery(array $callbackQuery): void
    {
        $callbackData    = $callbackQuery['data'] ?? '';
        $callbackQueryId = $callbackQuery['id'];
        $message         = $callbackQuery['message'] ?? null;

        if (str_starts_with($callbackData, 'open_assign_lead:')) {
            $this->handleOpenAssignLead($callbackQuery);
            return;
        }

        if (!str_starts_with($callbackData, 'assign_lead:')) {
            return;
        }

        // Parse: assign_lead:{lead_id}:{sale_id}
        $parts = explode(':', $callbackData);
        if (count($parts) !== 3) return;

        [, $leadId, $saleId] = $parts;

        $lead = CrmLead::with(['customer', 'user'])->find((int) $leadId);
        $sale = Customer::query()
            ->where('id', (int) $saleId)
            ->where(fn ($q) => $q->where('role', 'sale')->orWhere('role', 'sale_admin'))
            ->first();

        if (!$lead || !$sale) {
            $this->answerCallbackQuery($callbackQueryId, '❌ Không tìm thấy lead hoặc sale.');
            return;
        }

        // Already assigned
        if ($lead->sale_id) {
            $existingSale = Customer::find($lead->sale_id);
            $this->answerCallbackQuery(
                $callbackQueryId,
                "⚠️ Lead đã được gán cho {$existingSale?->name}.",
                true
            );
            return;
        }

        // Assign
        $lead->sale_id = $sale->id;
        $lead->save();

        CrmLeadActivity::create([
            'lead_id'  => $lead->id,
            'actor_id' => null, // bot-initiated
            'type'     => 'assignment',
            'content'  => "Phân công qua Telegram Group cho: {$sale->name}",
        ]);

        Log::info("TelegramBot: Lead #{$lead->id} assigned to Sale #{$sale->id} ({$sale->name}) via group keyboard.");

        // Answer the callback query (shows toast in Telegram)
        $this->answerCallbackQuery($callbackQueryId, "✅ Đã gán cho {$sale->name}!");

        // Edit the original group message — remove keyboard, append assignment info
        if ($message) {
            $originalText = $message['text'] ?? '';
            $newText      = $originalText . "\n\n✅ *Đã gán cho:* " . $this->escape($sale->name);
            $this->notificationService->editMessage(
                (string) $message['chat']['id'],
                (int) $message['message_id'],
                $newText
            );
        }

        // Notify assigned sale via private Telegram message
        if ($sale->telegram_id) {
            $privateMsg = TelegramMessageTemplates::leadAssigned($lead);
            $this->notificationService->sendToCustomer($sale, $privateMsg);
        }
    }

    /**
     * Xử lý callback open_assign_lead:{lead_id}:
     * Gửi private message đến người click chứa web_app button để mở Mini App phân công.
     */
    protected function handleOpenAssignLead(array $callbackQuery): void
    {
        $callbackData    = $callbackQuery['data'] ?? '';
        $callbackQueryId = $callbackQuery['id'];
        $fromId          = (string) ($callbackQuery['from']['id'] ?? '');

        // Parse: open_assign_lead:{lead_id}
        $parts  = explode(':', $callbackData);
        $leadId = (int) ($parts[1] ?? 0);

        if (!$leadId || !$fromId) {
            $this->answerCallbackQuery($callbackQueryId, '❌ Dữ liệu không hợp lệ.');
            return;
        }

        $lead = CrmLead::with(['customer', 'sale'])->find($leadId);
        if (!$lead) {
            $this->answerCallbackQuery($callbackQueryId, '❌ Không tìm thấy lead.');
            return;
        }

        // Nếu lead đã được assign, thông báo luôn
        if ($lead->sale_id) {
            $this->answerCallbackQuery(
                $callbackQueryId,
                "⚠️ Lead đã được gán cho {$lead->sale?->name}.",
                true
            );
            return;
        }

        $assignUrl = URL::temporarySignedRoute(
            'webapp.leads.assign-page',
            Carbon::now()->addHours(24),
            ['id' => $leadId]
        );

        $customerName = $lead->customer?->full_name ?? 'Khách hàng';
        $token        = Config::get('services.telegram.bot_token');

        // Gửi private message đến người click với web_app button
        $response = Http::post(
            "https://api.telegram.org/bot{$token}/sendMessage",
            [
                'chat_id'      => $fromId,
                'text'         => "🎯 *Phân công Lead #{$leadId}*\nKhách: " . $this->escape($customerName) . "\n\nBấm nút bên dưới để chọn sale phụ trách:",
                'parse_mode'   => 'Markdown',
                'reply_markup' => [
                    'inline_keyboard' => [[
                        ['text' => '👤 Mở trang phân công', 'web_app' => ['url' => $assignUrl]],
                    ]],
                ],
            ]
        );

        if ($response->successful()) {
            $this->answerCallbackQuery($callbackQueryId, '📩 Kiểm tra tin nhắn riêng của bạn!');
        } else {
            Log::warning("TelegramBot: Failed to send assign DM to {$fromId}. Response: " . $response->body());
            // Fallback: nếu không gửi được DM (user chưa chat với bot), đưa URL vào toast
            $this->answerCallbackQuery($callbackQueryId, "🔗 {$assignUrl}", true);
        }
    }

    protected function answerCallbackQuery(string $id, string $text, bool $showAlert = false): void
    {
        $token = Config::get('services.telegram.bot_token');
        if (!$token) return;

        Http::post(
            "https://api.telegram.org/bot{$token}/answerCallbackQuery",
            ['callback_query_id' => $id, 'text' => $text, 'show_alert' => $showAlert]
        );
    }

    private function escape(?string $text): string
    {
        return str_replace(['*', '_', '`', '['], ['\*', '\_', '\`', '\['], $text ?? '');
    }

    protected function handleMessage(array $message): void
    {
        $chatId = $message['chat']['id'] ?? null;
        if (!$chatId) return;

        $text = $message['text'] ?? '';
        $token = Config::get('services.telegram.bot_token');
        if (!$token) return;

        // Capture phone number if shared
        if (isset($message['contact'])) {
            $telegramId = $message['from']['id'] ?? null;
            $phoneNumber = $message['contact']['phone_number'] ?? '';
            $firstName = $message['from']['first_name'] ?? '';
            $lastName = $message['from']['last_name'] ?? '';

            if ($telegramId && $phoneNumber) {
                // Formatting phone number
                if (str_starts_with($phoneNumber, '+')) {
                    $phoneNumber = substr($phoneNumber, 1);
                }

                $customer = Customer::where('telegram_id', $telegramId)->first();
                if (!$customer) {
                    $fullName = trim($firstName . ' ' . $lastName);
                    if (empty($fullName)) {
                        $fullName = 'Thành viên mới';
                    }

                    $customer = Customer::create([
                        'name' => $fullName,
                        'full_name' => $fullName,
                        'mobile' => $phoneNumber,
                        'contact' => $phoneNumber,
                        'telegram_id' => $telegramId,
                        'role' => 'broker',
                    ]);
                } else {
                    $customer->mobile = $phoneNumber;
                    $customer->contact = $phoneNumber;

                    // Cập nhật lại tên thật nếu user đang bị dính tên ẩn danh của Guest
                    if (empty($customer->name) || in_array($customer->name, ['Khách', 'Khách vãng lai', 'Thành viên mới'])) {
                        $fullName = trim($firstName . ' ' . $lastName);
                        if (!empty($fullName)) {
                            $customer->name = $fullName;
                            $customer->full_name = $fullName;
                        }
                    }

                    // Nếu role chưa được set hoặc là guest → mặc định broker khi share phone
                    if (empty($customer->role) || $customer->role === 'guest' || !in_array($customer->role, Customer::VALID_ROLES)) {
                        $customer->role = 'broker';
                    }
                    $customer->save();
                }

                Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => "✅ Đã lưu số điện thoại của bạn thành công. Hãy quay lại WebApp để tiếp tục trải nghiệm!",
                    'reply_markup' => ['remove_keyboard' => true],
                ]);
            }
            return;
        }

        // Request phone number if text is /start
        if (str_starts_with($text, '/start')) {
            $telegramId = $message['from']['id'] ?? null;
            $customer = Customer::where('telegram_id', $telegramId)->first();

            // Store referral code if any (e.g. /start 12345)
            $parts = explode(' ', $text);
            if (count($parts) > 1 && $telegramId) {
                \Cache::put("pending_referral:{$telegramId}", $parts[1], now()->addHours(24));
            }

            if (!$customer || empty($customer->mobile)) {
                Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => "Chào bạn! Để sử dụng hệ thống Đà Lạt BĐS, vui lòng chia sẻ số điện thoại của bạn bằng cách nhấn vào nút bên dưới.",
                    'reply_markup' => [
                        'keyboard' => [
                            [
                                ['text' => '📱 Chia sẻ Số điện thoại', 'request_contact' => true]
                            ]
                        ],
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ]
                ]);
            } else {
                Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => "Chào mừng bạn quay lại Đà Lạt BĐS!",
                    'reply_markup' => ['remove_keyboard' => true],
                ]);
            }
        }
    }
}
