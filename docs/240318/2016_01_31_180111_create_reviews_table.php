<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('string_id')->unique();
            $table->string('status'); 
            $table->string('api_verb');
            $table->string('api_body');
            $table->string('hike_string_id');
            $table->timestamps();
            $table->integer('reviewer')->unsigned()->index();
            $table->foreign('reviewer')->references('id')->on('users')->onDelete('cascade');
            $table->integer('reviewee')->unsigned()->index();
            $table->foreign('reviewee')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reviews');
    }
}
