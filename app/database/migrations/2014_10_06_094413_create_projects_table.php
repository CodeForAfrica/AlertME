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
		Schema::create('projects', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('data_source_id')->default(0);
			$table->integer('data_source_config_id')->default(0);
			$table->integer('data_source_sync_id')->default(0);
			$table->string('project_id')->default('0');
			$table->mediumText('title')->nullable();
			$table->mediumText('description')->nullable();
			$table->string('geo_type')->default('lat_lng');
			$table->string('geo_address')->default('0');
			$table->string('geo_lat')->default('0');
			$table->string('geo_lng')->default('0');
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
