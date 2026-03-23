<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_deals_commissions', function (Blueprint $table) {
            $table->date('deposit_expected_date')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('crm_deals_commissions', function (Blueprint $table) {
            $table->dropColumn('deposit_expected_date');
        });
    }
};
