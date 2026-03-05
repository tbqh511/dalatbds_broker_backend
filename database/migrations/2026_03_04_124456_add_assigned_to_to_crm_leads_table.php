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
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_to')->nullable()->after('status')->comment('Assigned Sale (User ID)');
            $table->timestamp('assigned_at')->nullable()->after('assigned_to');
            
            // Assuming 'users' table is for internal staff/sales
            $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
            $table->index('assigned_to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropColumn(['assigned_to', 'assigned_at']);
        });
    }
};
