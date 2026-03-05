<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_deals_products', function (Blueprint $table) {
            $table->index('deal_id');
            $table->index('property_id');
        });

        Schema::table('crm_deals_commissions', function (Blueprint $table) {
            $table->index(['deal_id', 'sale_id']);
        });

        Schema::table('crm_deals_product_bookings', function (Blueprint $table) {
            // Index might already exist due to foreign key, but explicit index requested
            // Note: If foreign key created an index, this might create a duplicate index with different name
            $table->index('crm_deals_products_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_deals_products', function (Blueprint $table) {
            $table->dropIndex(['deal_id']);
            $table->dropIndex(['property_id']);
        });

        Schema::table('crm_deals_commissions', function (Blueprint $table) {
            $table->dropIndex(['deal_id', 'sale_id']);
        });

        Schema::table('crm_deals_product_bookings', function (Blueprint $table) {
            $table->dropIndex(['crm_deals_products_id']);
        });
    }
};
