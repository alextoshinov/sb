<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignHikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hikes', function($table)
        {
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
        Schema::table('hikes', function($table)
        {
            $table->dropForeign('hikes_location_id_foreign');
            $table->dropForeign('hikes_photo_facts_id_foreign');
            $table->dropForeign('hikes_photo_landscape_id_foreign');
            $table->dropForeign('hikes_photo_preview_id_foreign');
        });
    }
}
