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
        Schema::table('crm_deals', function (Blueprint $table) {
            if (! Schema::hasColumn('crm_deals', 'share_code')) {
                $table->string('share_code', 32)->nullable()->unique()->after('lead_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_deals', function (Blueprint $table) {
            if (Schema::hasColumn('crm_deals', 'share_code')) {
                $table->dropUnique(['share_code']);
                $table->dropColumn('share_code');
            }
        });
    }
};
