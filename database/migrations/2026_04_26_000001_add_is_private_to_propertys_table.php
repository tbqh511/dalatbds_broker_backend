<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('propertys', function (Blueprint $table) {
            $table->tinyInteger('is_private')->default(0)->after('status')
                  ->comment('0=public, 1=private (chỉ owner và privileged roles thấy)');
        });
    }

    public function down(): void
    {
        Schema::table('propertys', function (Blueprint $table) {
            $table->dropColumn('is_private');
        });
    }
};
