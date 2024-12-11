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
        Schema::create('crm_deals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->text('notes')->nullable();
            $table->enum('status', ['new', 'prospecting', 'negotiating', 'deposit_paid', 'pending_notary', 'win', 'lost'])->default('new');
            $table->double('amount')->default(0);
            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->foreign('customer_id')->references('id')->on('crm_customers');
            $table->foreign('last_updated_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_deals');
    }
};
