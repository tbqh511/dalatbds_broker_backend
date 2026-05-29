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
        Schema::table('crm_customers', function (Blueprint $table) {
            // Broker sở hữu bản ghi khách hàng (= Customer.id). Nullable cho dữ liệu cũ.
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->index(['user_id', 'contact'], 'crm_customers_user_contact_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_customers', function (Blueprint $table) {
            $table->dropIndex('crm_customers_user_contact_index');
            $table->dropColumn('user_id');
        });
    }
};
