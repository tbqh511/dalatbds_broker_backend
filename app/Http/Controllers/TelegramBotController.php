<?php

namespace App\Http\Controllers;

use App\Models\CrmLead;
use App\Models\CrmLeadActivity;
use App\Models\Customer;
use App\Services\NotificationService;
use App\Services\Telegram\TelegramMessageTemplates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
