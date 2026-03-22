<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('address');
            $table->string('zalo', 20)->nullable()->after('bio');
            $table->string('facebook_link', 255)->nullable()->after('zalo');
            $table->tinyInteger('years_experience')->unsigned()->nullable()->after('facebook_link');
            $table->string('work_area', 255)->nullable()->after('years_experience');
            $table->string('specialization', 255)->nullable()->after('work_area');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['bio', 'zalo', 'facebook_link', 'years_experience', 'work_area', 'specialization']);
        });
    }
};
