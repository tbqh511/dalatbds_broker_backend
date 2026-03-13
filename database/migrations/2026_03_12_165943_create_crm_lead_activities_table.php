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
        Schema::create('crm_lead_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');
            $table->unsignedBigInteger('actor_id')->nullable(); // nullable: bot-initiated actions have no actor
            $table->string('type'); // call, note, assignment, status_change
            $table->text('content')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('lead_id')->references('id')->on('crm_leads')->cascadeOnDelete();
            $table->foreign('actor_id')->references('id')->on('customers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_lead_activities');
    }
};
