<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('propertys', function (Blueprint $table) {
            $table->unsignedBigInteger('approved_by')->nullable()->after('rejection_note');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('approved_at');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');

            $table->foreign('approved_by')->references('id')->on('customers')->nullOnDelete();
            $table->foreign('rejected_by')->references('id')->on('customers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('propertys', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn(['approved_by', 'approved_at', 'rejected_by', 'rejected_at']);
        });
    }
};
