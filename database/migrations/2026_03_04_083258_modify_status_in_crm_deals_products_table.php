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
        // Standardize status column in crm_deals_products table
        // Note: This might cause data loss for existing rows with status values not in the new list.
        DB::statement("ALTER TABLE crm_deals_products MODIFY COLUMN status ENUM('sent_info', 'sent_location', 'sent_legal', 'customer_feedback', 'booking_created', 'viewed_success', 'viewed_failed', 'negotiating', 'waiting_finance') NULL DEFAULT 'sent_info'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert to original status values
        DB::statement("ALTER TABLE crm_deals_products MODIFY COLUMN status ENUM('Sent', 'Sales nurturing', 'Negotiating', 'Not interested') NULL");
    }
};
