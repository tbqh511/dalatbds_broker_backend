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
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('source_note', 255)->nullable();
            $table->enum('lead_type', ['buy', 'rent'])->nullable();
            $table->string('categories', 255)->nullable(); // List of category IDs
            $table->string('wards', 255)->nullable(); // List of ward codes
            $table->double('demand_rate_min')->default(0);
            $table->double('demand_rate_max')->default(0);
            $table->string('demand_legal', 255)->nullable();
            $table->string('note', 255)->nullable();
            $table->enum('status', ['new', 'contacted', 'converted', 'bad-contact', 'lost'])->default('new');
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
        Schema::dropIfExists('crm_leads');
    }
};
