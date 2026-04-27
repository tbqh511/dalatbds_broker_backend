<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->string('budget_label')->nullable()->after('demand_rate_max');
        });
    }

    public function down()
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->dropColumn('budget_label');
        });
    }
};
