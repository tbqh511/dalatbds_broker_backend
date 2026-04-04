<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Đổi cột propertys.added_by từ TINYINT → INT UNSIGNED.
     *
     * TINYINT signed chỉ chứa tối đa 127. Khi Customer ID vượt quá 127,
     * MySQL tự clip giá trị xuống 127 mà không báo lỗi — gây bug
     * "BĐS không hiện trong My Listings" vì filter added_by không match.
     */
    public function up()
    {
        Schema::table('propertys', function (Blueprint $table) {
            $table->unsignedInteger('added_by')->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('propertys', function (Blueprint $table) {
            $table->tinyInteger('added_by')->default(0)->change();
        });
    }
};
