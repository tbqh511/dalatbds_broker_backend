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
        // Update existing NULL values to 1 (active) before modifying the column
        DB::table('customers')
            ->whereNull('isActive')
            ->update(['isActive' => 1]);

        // Use raw SQL to add default constraint to isActive column
        // This avoids Doctrine DBAL issues with changing existing columns
        DB::statement('ALTER TABLE customers MODIFY isActive TINYINT NOT NULL DEFAULT 1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to no default value
        DB::statement('ALTER TABLE customers MODIFY isActive TINYINT NULL');
    }
};
