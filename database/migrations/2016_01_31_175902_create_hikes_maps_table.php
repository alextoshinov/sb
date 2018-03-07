<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHikesMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hikes_maps', function (Blueprint $table) {
            $table->integer('hike_id')->unsigned()->index();
            $table->foreign('hike_id')->references('id')->on('hikes');
            $table->integer('map_id')->unsigned()->index();
            $table->foreign('map_id')->references('id')->on('maps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hikes_maps');
    }
}
