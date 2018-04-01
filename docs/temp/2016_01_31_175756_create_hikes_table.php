<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hikes', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('string_id')->unique()->index();
            $table->integer('location_id')->unsigned()->index();
            $table->integer('photo_facts_id')->unsigned()->index();
            $table->integer('photo_landscape_id')->unsigned()->index();
            $table->integer('photo_preview_id')->unsigned()->index();            
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('locality')->nullable();
            $table->string('permit');
            $table->text('route')->nullable();
            $table->float('distance');
            $table->float('elevation_gain');
            $table->float('elevation_max');
            $table->boolean('is_featured');
            $table->timestamps();

            $table->foreign('location_id')
                  ->references('id')->on('locations');  
            $table->foreign('photo_facts_id')
                  ->references('id')->on('photos');     
            $table->foreign('photo_landscape_id')
                  ->references('id')->on('photos'); 
            $table->foreign('photo_preview_id')
                  ->references('id')->on('photos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hikes');
    }
}
