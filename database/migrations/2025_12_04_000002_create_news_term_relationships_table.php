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
        Schema::create('news_term_relationships', function (Blueprint $table) {
            $table->unsignedBigInteger('object_id')->default(0);
            $table->unsignedBigInteger('term_taxonomy_id')->default(0);
            $table->integer('term_order')->default(0);

            $table->primary(['object_id', 'term_taxonomy_id']);
            $table->index('term_taxonomy_id');

            // Ideally we would reference news_posts but object_id can be other things in WP.
            // However here it's likely just posts. But to keep it flexible like WP we might not force FK to news_posts immediately
            // OR we do since we are in Laravel and referential integrity is good.
            // The prompt asks to mimic WP, WP doesn't strictly enforce FKs on object_id to wp_posts in DB schema level traditionally (it's loose),
            // but for a robust Laravel app, FK is better.
            // But let's check if news_posts ID is 'ID' (bigint). Yes.
            // Let's add FK for safety if possible, but keep in mind object_id could be links or other things if fully implementing WP.
            // Given the user only showed news_posts, I will assume object_id refers to news_posts.ID.

            $table->foreign('object_id')->references('ID')->on('news_posts')->onDelete('cascade');
            $table->foreign('term_taxonomy_id')->references('term_taxonomy_id')->on('news_term_taxonomy')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_term_relationships');
    }
};
