<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Đổi kiểu cột telegram_id từ INT sang BIGINT UNSIGNED.
 *
 * Vấn đề: Telegram user ID có thể vượt quá giới hạn INT 32-bit (max ~2.1 tỷ).
 * Ví dụ: ID 5035651415 bị wrap thành 740684119 khi lưu vào cột INT,
 * khiến lookup thất bại và user không thể đăng nhập vào webapp.
 *
 * Bằng chứng: 5035651415 - 4294967296 = 740684119 (32-bit wrap-around).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('telegram_id')->nullable()->change();
        });

        // Fix dữ liệu bị hỏng: customer #1041 có telegram_id = 740684119
        // do overflow từ giá trị thực 5035651415.
        // Chỉ update nếu chưa có customer khác đang giữ đúng telegram_id này.
        $alreadyCorrect = DB::table('customers')
            ->where('telegram_id', '5035651415')
            ->where('id', '!=', 1041)
            ->exists();

        if (! $alreadyCorrect) {
            DB::table('customers')
                ->where('id', 1041)
                ->where('telegram_id', 740684119)
                ->update(['telegram_id' => '5035651415']);

            \Illuminate\Support\Facades\Log::info(
                'Migration fix_telegram_id: restored customer #1041 telegram_id from 740684119 to 5035651415'
            );
        } else {
            \Illuminate\Support\Facades\Log::warning(
                'Migration fix_telegram_id: skipped customer #1041 update — another customer already holds telegram_id 5035651415'
            );
        }
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('telegram_id')->nullable()->change();
        });
    }
};
