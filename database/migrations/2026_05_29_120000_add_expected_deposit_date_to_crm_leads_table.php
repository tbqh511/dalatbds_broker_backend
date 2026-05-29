<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ngày dự kiến đặt cọc — Sale nhập khi xác nhận nhu cầu khách ("Bắt đầu chăm").
     */
    public function up(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->date('expected_deposit_date')->nullable()->after('budget_label');
        });
    }

    public function down(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->dropColumn('expected_deposit_date');
        });
    }
};
