<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::table('propertys', function (Blueprint $table) {
            $table->tinyInteger('status')
                ->comment('0: pending, 1: active, 2: rejected, 3: private, 4: sold, 5: rented, 6: archived')
                ->change();
        });
    }

    public function down()
    {
        Schema::table('propertys', function (Blueprint $table) {
            $table->tinyInteger('status')->change(); // Xóa comment nếu cần
        });
    }
};
