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
        if (!Schema::hasTable('crm_deals_product_bookings')) {
            Schema::create('crm_deals_product_bookings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('crm_deals_products_id');
                $table->date('booking_date')->nullable();
                $table->time('booking_time')->nullable();
                // Status values were not specified in the task, using standard booking statuses
                $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
                $table->text('customer_feedback')->nullable();
                $table->text('internal_note')->nullable();
                $table->timestamps();

                $table->foreign('crm_deals_products_id')->references('id')->on('crm_deals_products')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_deals_product_bookings');
    }
};
