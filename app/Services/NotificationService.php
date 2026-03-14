<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class NotificationService
{
    protected string $botToken;
    protected string $apiUrl;
    protected string $editUrl;

    public function __construct()
    {
        $this->botToken = Config::get('services.telegram.bot_token');
        $this->apiUrl = "https://api.telegram.org/bot{$this->botToken}/sendMessage";
        $this->editUrl = "https://api.telegram.org/bot{$this->botToken}/editMessageText";
    }

    /**
     * Send message to a specific User (Broker/Admin)
     */
    public function sendToUser(User $user, string $message, array $options = []): bool
    {
        if (!$user->telegram_id) {
            Log::warning("NotificationService: User {$user->id} does not have a telegram_id.");
            return false;
        }

        return $this->sendRaw($user->telegram_id, $message, $options);
    }

    /**
     * Send message to a Customer
     */
    public function sendToCustomer(Customer $customer, string $message, array $options = []): bool
    {
        if (!$customer->telegram_id) {
            Log::warning("NotificationService: Customer {$customer->id} does not have a telegram_id.");
            return false;
        }

        return $this->sendRaw($customer->telegram_id, $message, $options);
    }

    /**
     * Send message to a configured Group
     * @param string $groupKey Key from config/services.php (e.g., 'public_channel', 'sale_admin')
     */
    public function sendToGroup(string $groupKey, string $message, array $options = []): bool
    {
        $chatId = Config::get("services.telegram.groups.{$groupKey}");

        if (!$chatId) {
            Log::error("NotificationService: Group key '{$groupKey}' not found in configuration.");
            return false;
        }

        return $this->sendRaw($chatId, $message, $options);
    }

    /**
     * Send message with Telegram inline keyboard to a chat.
     * Returns the message_id of the sent message, or null on failure.
     */
    public function sendWithInlineKeyboard(string $chatId, string $message, array $inlineKeyboard): ?int
    {
        if (!$this->botToken) {
            Log::error("NotificationService: Telegram Bot Token is not configured.");
            return null;
        }

        try {
            $response = Http::retry(3, 100)->post($this->apiUrl, [
                'chat_id'      => $chatId,
                'text'         => $message,
                'parse_mode'   => 'Markdown',
                'reply_markup' => ['inline_keyboard' => $inlineKeyboard],
            ]);

            if ($response->successful()) {
                Log::info("NotificationService: Message with keyboard sent to {$chatId}.");
                return $response->json('result.message_id');
            }

            Log::error("NotificationService: Failed to send keyboard message to {$chatId}. Response: " . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error("NotificationService: Exception sending keyboard message to {$chatId}. Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Edit an existing message (e.g. to remove inline keyboard after assignment).
     */
    public function editMessage(string $chatId, int $messageId, string $newText): bool
    {
        if (!$this->botToken) return false;

        try {
            $response = Http::retry(3, 100)->post($this->editUrl, [
                'chat_id'      => $chatId,
                'message_id'   => $messageId,
                'text'         => $newText,
                'parse_mode'   => 'Markdown',
                'reply_markup' => ['inline_keyboard' => []],
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("NotificationService: Exception editing message {$messageId}. Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send raw message to Telegram API with retry logic
     */
    protected function sendRaw(string $chatId, string $message, array $options = []): bool
    {
        if (!$this->botToken) {
            Log::error("NotificationService: Telegram Bot Token is not configured.");
            return false;
        }

        try {
            $payload = [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown', // Or 'MarkdownV2' if templates use it
            ];

            if (!empty($options)) {
                $payload = array_merge($payload, $options);
            }

            // Retry 3 times with 100ms delay
            $response = Http::retry(3, 100)->post($this->apiUrl, $payload);

            if ($response->successful()) {
                Log::info("NotificationService: Message sent to {$chatId}.");
                return true;
            } else {
                Log::error("NotificationService: Failed to send to {$chatId}. Response: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("NotificationService: Exception sending to {$chatId}. Error: " . $e->getMessage());
            return false;
        }
    }
}
