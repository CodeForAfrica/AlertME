<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->engine = 'MyISAM';

            $table->increments('id');
            $table->integer('data_source_id')->default(0);
            $table->integer('data_source_config_id')->default(0);
            $table->integer('data_source_sync_id')->default(0);
            $table->string('project_id')->default('0');
            $table->string('title')->default('[No Title]');
            $table->longText('description')->nullable();
            $table->string('geo_type')->default('lat_lng');
            $table->string('geo_address')->default('0');
            $table->decimal('geo_lat', 65, 10)->default(0);
            $table->decimal('geo_lng', 65, 10)->default(0);
            $table->string('status')->nullable();
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
        Schema::drop('projects');
    }

}
