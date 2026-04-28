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
            $tpl = TelegramMessageTemplates::leadAssigned($lead);
            $this->notificationService->sendWithInlineKeyboard($sale->telegram_id, $tpl['text'], $tpl['keyboard']);
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

    private function sendReferralNotification(Customer $referrer, Customer $newUser): void
    {
        try {
            $inAppService = app(\App\Services\InAppNotificationService::class);

            // 1. Telegram message
            if ($referrer->telegram_id && $this->notificationService->shouldNotify($referrer, 'referral', 'new_signup', 'telegram')) {
                $message = \App\Services\Telegram\TelegramMessageTemplates::referralNewSignup($referrer, $newUser);
                $this->notificationService->sendToCustomer($referrer, $message);
            }

            // 2. In-app notification
            $inAppService->notify(
                $referrer,
                'referral_new_signup',
                'referral',
                'new_signup',
                [
                    'title' => 'Có người đăng ký qua mã giới thiệu của bạn!',
                    'body'  => ($newUser->name ?? 'Thành viên mới') . ' vừa tham gia Đà Lạt BĐS qua link của bạn.',
                    'notifiable_type' => Customer::class,
                    'notifiable_id'   => $newUser->id,
                    'actor_id'        => $newUser->id,
                    'data'  => [
                        'referred_id'   => $newUser->id,
                        'referred_name' => $newUser->name ?? '',
                        'referral_code' => $referrer->referral_code,
                    ],
                ]
            );
        } catch (\Exception $e) {
            \Log::error("Referral notification failed (Bot contact): " . $e->getMessage());
        }
    }

    private function sendWelcomeNotification(Customer $customer): void
    {
        try {
            $firstName = explode(' ', trim($customer->name ?? ''))[0] ?: 'bạn';
            $firstName = mb_convert_case($firstName, MB_CASE_TITLE, 'UTF-8');

            $welcomeMessages = [
                [
                    'title' => "Chào mừng {$firstName} gia nhập Đà Lạt BĐS!",
                    'body'  => 'Hành trình của bạn bắt đầu từ hôm nay. Mỗi giao dịch thành công đều khởi nguồn từ một bước đầu tiên dũng cảm như bước bạn vừa làm!',
                ],
                [
                    'title' => "Tuyệt vời {$firstName}, bạn đã sẵn sàng!",
                    'body'  => 'Đà Lạt BĐS luôn đồng hành cùng bạn. Hãy bắt đầu bằng việc đăng tin BĐS đầu tiên hoặc thêm khách hàng tiềm năng vào danh sách lead nhé!',
                ],
                [
                    'title' => "Chào {$firstName}, chào mừng đến với đội ngũ!",
                    'body'  => 'Bạn đã gia nhập cộng đồng môi giới BĐS Đà Lạt. Mỗi ngày là một cơ hội mới — hãy tận dụng từng khoảnh khắc và kết quả sẽ đến đúng lúc!',
                ],
            ];

            $picked = $welcomeMessages[($customer->id ?? 0) % count($welcomeMessages)];

            \App\Models\InAppNotification::create([
                'customer_id' => $customer->id,
                'type'        => 'welcome_ebroker',
                'category'    => 'system',
                'title'       => $picked['title'],
                'body'        => $picked['body'],
                'data'        => ['action' => 'onboard'],
            ]);
        } catch (\Exception $e) {
            \Log::error("Welcome notification failed for customer #{$customer->id}: " . $e->getMessage());
        }
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

            Log::info('[BotContact] received contact', [
                'telegram_id'   => $telegramId,
                'raw_phone'     => $phoneNumber,
                'contact_user_id' => $message['contact']['user_id'] ?? null,
                'from_username' => $message['from']['username'] ?? null,
            ]);

            if ($telegramId && $phoneNumber) {
                // Formatting phone number
                if (str_starts_with($phoneNumber, '+')) {
                    $phoneNumber = substr($phoneNumber, 1);
                }
                // Chuẩn hóa: luôn lưu format quốc tế 84xxxxxxxxx
                // Telegram gửi +84xxx → đã strip dấu + ở trên → giữ nguyên 84xxx
                // Nếu dạng 0xxx → chuyển thành 84xxx
                if (preg_match('/^0(\d{9})$/', $phoneNumber, $m)) {
                    $phoneNumber = '84' . $m[1];
                }

                Log::info('[BotContact] phone after normalize', [
                    'telegram_id'      => $telegramId,
                    'normalized_phone' => $phoneNumber,
                ]);

                $customer = Customer::where('telegram_id', (string) $telegramId)->orderBy('id', 'desc')->first();

                Log::info('[BotContact] lookup by telegram_id', [
                    'telegram_id' => $telegramId,
                    'found'       => $customer ? true : false,
                    'customer_id' => $customer->id ?? null,
                ]);

                // Fallback: match by phone if telegram_id doesn't match (e.g. user has 2 accounts)
                if (!$customer) {
                    // Tìm cả format mới (84xxx) và format cũ (0xxx) để backward compatible
                    $phoneVariants = [$phoneNumber];
                    if (preg_match('/^84(\d{9})$/', $phoneNumber, $m)) {
                        $phoneVariants[] = '0' . $m[1];
                    }
                    $customer = Customer::whereIn('mobile', $phoneVariants)->first();
                    Log::info('[BotContact] fallback lookup by phone', [
                        'phone'       => $phoneNumber,
                        'found'       => $customer ? true : false,
                        'customer_id' => $customer->id ?? null,
                        'customer_mobile' => $customer->mobile ?? null,
                    ]);
                    if ($customer) {
                        Log::info("TelegramBot contact: phone match found customer #{$customer->id}, updating telegram_id from [{$customer->telegram_id}] to [{$telegramId}]");
                        $customer->telegram_id = (string) $telegramId;
                        $customer->telegram_bot_started = true;
                    }
                }

                if (!$customer) {
                    $fullName = trim($firstName . ' ' . $lastName);
                    if (empty($fullName)) {
                        $fullName = 'Thành viên mới';
                    }

                    Log::info('[BotContact] no existing customer found, creating new', [
                        'telegram_id' => $telegramId,
                        'phone'       => $phoneNumber,
                        'name'        => $fullName,
                    ]);

                    try {
                        $customer = Customer::create([
                            'name' => $fullName,
                            'mobile' => $phoneNumber,
                            'telegram_id' => (string) $telegramId,
                            'telegram_bot_started' => true,
                            'role' => 'broker',
                            'isActive' => 1,
                        ]);
                        Log::info('[BotContact] new customer created', ['customer_id' => $customer->id]);
                    } catch (\Illuminate\Database\QueryException $e) {
                        if ($e->getCode() === '23000') {
                            // Duplicate mobile — tìm lại record đã tồn tại và cập nhật telegram_id
                            Log::warning('[BotContact] duplicate mobile on create, falling back to update', [
                                'phone' => $phoneNumber,
                                'telegram_id' => $telegramId,
                            ]);
                            $phoneVariants = [$phoneNumber];
                            if (preg_match('/^84(\d{9})$/', $phoneNumber, $m)) {
                                $phoneVariants[] = '0' . $m[1];
                            }
                            $customer = Customer::whereIn('mobile', $phoneVariants)->first();
                            if ($customer) {
                                $customer->telegram_id = (string) $telegramId;
                                $customer->telegram_bot_started = true;
                                $customer->save();
                                Log::info('[BotContact] updated existing customer after duplicate', ['customer_id' => $customer->id]);
                            }
                        } else {
                            throw $e;
                        }
                    }
                } else {
                    $customer->mobile = $phoneNumber;

                    // Cập nhật lại tên thật nếu user đang bị dính tên ẩn danh của Guest
                    if (empty($customer->name) || in_array($customer->name, ['Khách', 'Khách vãng lai', 'Thành viên mới'])) {
                        $fullName = trim($firstName . ' ' . $lastName);
                        if (!empty($fullName)) {
                            $customer->name = $fullName;
                        }
                    }

                    // Nếu role chưa được set hoặc là guest → mặc định broker khi share phone
                    if (empty($customer->role) || $customer->role === 'guest' || !in_array($customer->role, Customer::VALID_ROLES)) {
                        $customer->role = 'broker';
                    }
                    $customer->save();
                    Log::info('[BotContact] existing customer updated', [
                        'customer_id'  => $customer->id,
                        'telegram_id'  => $customer->telegram_id,
                        'role'         => $customer->role,
                    ]);
                }

                // Gán người giới thiệu từ cache nếu chưa có
                $cacheKey = "pending_referral:{$telegramId}";
                $refCode = \Cache::pull($cacheKey);
                if (!empty($refCode) && empty($customer->referred_by)) {
                    if (str_starts_with($refCode, 'ref_')) {
                        $refCode = substr($refCode, 4);
                    }
                    $referrer = Customer::where('referral_code', $refCode)->first();
                    if ($referrer && $referrer->id !== $customer->id) {
                        $customer->referred_by = $referrer->id;
                        $customer->save();
                        \Log::info("Referral assigned via Bot contact: Customer #{$customer->id} referred by #{$referrer->id} (code: {$refCode})");
                        $this->sendReferralNotification($referrer, $customer);
                    }
                }

                // Gửi welcome in-app notification (chỉ nếu chưa có welcome trước đó)
                if (!\App\Models\InAppNotification::where('customer_id', $customer->id)->where('type', 'welcome_ebroker')->exists()) {
                    $this->sendWelcomeNotification($customer);
                }

                Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => "🎉 Chào mừng bạn gia nhập đội ngũ môi giới DalatBDS!\n\nSố điện thoại đã được xác nhận thành công. Từ giờ bạn có thể đăng tin, quản lý khách hàng và theo dõi hoa hồng ngay trên ứng dụng.\n\nChúc bạn nhiều giao dịch thành công! 💪🏡",
                    'reply_markup' => ['remove_keyboard' => true],
                ]);
            }
            return;
        }

        // Request phone number if text is /start
        if (str_starts_with($text, '/start')) {
            $telegramId = $message['from']['id'] ?? null;
            $parts      = explode(' ', $text);
            $startParam = $parts[1] ?? null;

            // Handle /start link_{token} — broker clicked "Bật thông báo" in WebApp
            if ($startParam && str_starts_with($startParam, 'link_')) {
                $token_cache_key = "bot_link_token:{$startParam}";
                $customerId = \Cache::pull($token_cache_key);

                if ($customerId && $telegramId) {
                    $customer = Customer::find($customerId);
                    if ($customer) {
                        $oldId = $customer->telegram_id;
                        $customer->telegram_id = (string) $telegramId;
                        $customer->telegram_bot_started = true;
                        $customer->save();
                        Log::info("TelegramBot link: customer #{$customer->id} telegram_id updated [{$oldId}] → [{$telegramId}]");

                        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                            'chat_id'      => $chatId,
                            'text'         => "Thông báo Telegram đã được bật thành công!\n\nBạn sẽ nhận tin nhắn trực tiếp từ bot khi đăng tin, được giao lead và các cập nhật quan trọng.",
                            'reply_markup' => ['remove_keyboard' => true],
                        ]);
                        return;
                    }
                }

                // Token expired or invalid — fall through to normal /start flow
                Log::warning("TelegramBot link: invalid or expired token [{$startParam}] from from_id={$telegramId}");
            }

            $customer = Customer::where('telegram_id', (string) $telegramId)->first();

            Log::info('[BotStart] /start lookup', [
                'telegram_id' => $telegramId,
                'chat_id'     => $chatId,
                'found'       => $customer ? true : false,
                'customer_id' => $customer->id ?? null,
                'has_phone'   => $customer ? !empty($customer->mobile) : null,
            ]);

            // Mark bot as started — enables DM notifications from the app
            if ($customer && !$customer->telegram_bot_started) {
                $customer->telegram_bot_started = true;
                $customer->save();
            }

            // Store referral code if any (e.g. /start ref_DLBDS-XXXXX)
            if ($startParam && str_starts_with($startParam, 'ref_') && $telegramId) {
                $refCode = substr($startParam, 4);
                \Cache::put("pending_referral:{$telegramId}", $refCode, now()->addHours(24));
            } elseif ($startParam && !str_starts_with($startParam, 'link_') && $telegramId) {
                // Legacy: raw referral code without ref_ prefix
                \Cache::put("pending_referral:{$telegramId}", $startParam, now()->addHours(24));
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
