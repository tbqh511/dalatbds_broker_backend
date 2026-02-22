<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('propertys') && Schema::hasColumn('propertys', 'propery_type') && !Schema::hasColumn('propertys', 'property_type')) {
            DB::statement("ALTER TABLE `propertys` CHANGE `propery_type` `property_type` TINYINT(4) NOT NULL COMMENT '0:Sell 1:Rent'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('propertys') && Schema::hasColumn('propertys', 'property_type') && !Schema::hasColumn('propertys', 'propery_type')) {
            DB::statement("ALTER TABLE `propertys` CHANGE `property_type` `propery_type` TINYINT(4) NOT NULL COMMENT '0:Sell 1:Rent'");
        }
    }
};

