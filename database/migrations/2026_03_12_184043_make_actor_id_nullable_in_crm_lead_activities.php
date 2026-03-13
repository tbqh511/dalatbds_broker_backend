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
        Schema::table('crm_lead_activities', function (Blueprint $table) {
            $table->dropForeign(['actor_id']);
            $table->unsignedBigInteger('actor_id')->nullable()->change();
            $table->foreign('actor_id')->references('id')->on('customers')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('crm_lead_activities', function (Blueprint $table) {
            $table->dropForeign(['actor_id']);
            $table->unsignedBigInteger('actor_id')->nullable(false)->change();
            $table->foreign('actor_id')->references('id')->on('customers')->cascadeOnDelete();
        });
    }
};
