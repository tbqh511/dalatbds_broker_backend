<?php

namespace App\Http\Traits;

trait ValidatesTelegramInitData
{
    /**
     * Xác thực chữ ký Telegram initData.
     * Trả về mảng thông tin user nếu hợp lệ, null nếu không.
     */
    protected function validateTelegramInitData(string $initData): ?array
    {
        parse_str($initData, $data);

        if (!isset($data['hash'])) {
            return null;
        }

        $receivedHash = $data['hash'];
        unset($data['hash']);

        ksort($data);

        $dataCheckArr = [];
        foreach ($data as $key => $value) {
            $dataCheckArr[] = $key . '=' . $value;
        }
        $dataCheckString = implode("\n", $dataCheckArr);

        $botToken = env('TELEGRAM_BOT_TOKEN');
        if (!$botToken) {
            return null;
        }

        $secretKey = hash_hmac('sha256', $botToken, "WebAppData", true);
        $calculatedHash = bin2hex(hash_hmac('sha256', $dataCheckString, $secretKey, true));

        if (strcmp($calculatedHash, $receivedHash) !== 0) {
            return null;
        }

        if (isset($data['auth_date']) && (time() - $data['auth_date'] > 86400)) {
            return null;
        }

        if (!isset($data['user'])) {
            return null;
        }

        return json_decode($data['user'], true);
    }
}
