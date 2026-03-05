<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Use raw SQL to modify ENUM column as Doctrine DBAL has limitations with ENUM
        DB::statement("ALTER TABLE crm_deals_product_bookings MODIFY COLUMN status ENUM('scheduled', 'completed_success', 'completed_negotiating', 'completed_failed', 'rescheduled', 'cancelled') NOT NULL DEFAULT 'scheduled'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert to previous enum values (approximate)
        DB::statement("ALTER TABLE crm_deals_product_bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled', 'completed') NOT NULL DEFAULT 'pending'");
    }
};
