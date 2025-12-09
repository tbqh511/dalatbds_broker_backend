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
        if (!Schema::hasColumn('customers', 'telegram_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('telegram_id')->nullable()->after('api_token')->comment('Telegram user id for bot login');
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
        if (Schema::hasColumn('customers', 'telegram_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('telegram_id');
            });
        }
    }
};
