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
        Schema::create('news_postmeta', function (Blueprint $table) {
            $table->id('meta_id');
            $table->unsignedBigInteger('news_post_id')->default(0);
            $table->string('meta_key', 255)->nullable()->index();
            $table->longText('meta_value')->nullable();
            $table->timestamps();

            $table->foreign('news_post_id')->references('ID')->on('news_posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_postmeta');
    }
};