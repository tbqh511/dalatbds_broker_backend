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
        Schema::create('news_term_taxonomy', function (Blueprint $table) {
            $table->id('term_taxonomy_id');
            $table->unsignedBigInteger('term_id')->default(0);
            $table->string('taxonomy', 32)->index();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('parent')->default(0);
            $table->bigInteger('count')->default(0);
            $table->timestamps();

            $table->foreign('term_id')->references('term_id')->on('news_terms')->onDelete('cascade');
            $table->index(['term_id', 'taxonomy'], 'term_id_taxonomy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_term_taxonomy');
    }
};
