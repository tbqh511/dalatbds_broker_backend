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
        Schema::table('crm_deals_commissions', function (Blueprint $table) {
            $table->enum('status', [
                'pending_deposit', 
                'deposited', 
                'notarizing', 
                'completed', 
                'cancelled'
            ])->default('pending_deposit')->after('owner_commission');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_deals_commissions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
