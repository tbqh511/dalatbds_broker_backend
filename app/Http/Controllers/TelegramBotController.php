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
}
