<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Add default value of 1 (active) to isActive column
            // This ensures newly created users are active by default
            $table->tinyInteger('isActive')->default(1)->change();
        });

        // Update existing NULL values to 1 (active)
        DB::table('customers')
            ->whereNull('isActive')
            ->update(['isActive' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Revert to no default value
            $table->tinyInteger('isActive')->nullable()->change();
        });
    }
};
