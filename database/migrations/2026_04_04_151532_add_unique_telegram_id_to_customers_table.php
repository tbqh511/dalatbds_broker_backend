<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm UNIQUE constraint cho customers.telegram_id.
     *
     * QUAN TRỌNG: Chạy migration này SAU khi đã cleanup duplicate telegram_id.
     * Nếu còn duplicate trong DB, migration này sẽ thất bại.
     * Trước khi chạy, hãy chạy query sau để cleanup:
     *
     *   -- Tìm duplicate:
     *   SELECT telegram_id, COUNT(*) as cnt, GROUP_CONCAT(id ORDER BY id DESC) as ids
     *   FROM customers WHERE telegram_id IS NOT NULL AND telegram_id != ''
     *   GROUP BY telegram_id HAVING cnt > 1;
     *
     *   -- Xóa telegram_id khỏi account cũ (giữ lại account mới nhất):
     *   UPDATE customers SET telegram_id = NULL
     *   WHERE id = <id_cũ> AND telegram_id = '<telegram_id>';
     */
    public function up()
    {
        // Xóa duplicate trước khi thêm unique index
        // Giữ lại telegram_id cho customer có ID cao nhất, clear các customer cũ hơn
        $duplicates = DB::select("
            SELECT telegram_id, MIN(id) as old_id
            FROM customers
            WHERE telegram_id IS NOT NULL AND telegram_id != ''
            GROUP BY telegram_id
            HAVING COUNT(*) > 1
        ");

        foreach ($duplicates as $dup) {
            DB::table('customers')
                ->where('telegram_id', $dup->telegram_id)
                ->where('id', $dup->old_id)
                ->update(['telegram_id' => null]);

            \Illuminate\Support\Facades\Log::info(
                "Migration: cleared duplicate telegram_id '{$dup->telegram_id}' from customer #{$dup->old_id}"
            );
        }

        Schema::table('customers', function (Blueprint $table) {
            $table->unique('telegram_id', 'customers_telegram_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique('customers_telegram_id_unique');
        });
    }
};
