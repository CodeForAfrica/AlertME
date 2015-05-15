<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexes extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('projects', function ($table) {
            $table->index('data_source_id');
            $table->index('project_id');
            $table->index('title');
            $table->index('geo_type');
            $table->index('geo_address');
            $table->index('geo_lat');
            $table->index('geo_lng');
        });

        Schema::table('geocodes', function ($table) {
            $table->index('address');
            $table->index('lat');
            $table->index('lng');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
