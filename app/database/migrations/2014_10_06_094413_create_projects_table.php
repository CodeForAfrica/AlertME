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
			$table->integer('data_source_id');
			$table->integer('data_source_config_id');
			$table->integer('data_source_sync_id');
			$table->mediumText('project_id');
			$table->mediumText('title');
			$table->mediumText('description');
			$table->mediumText('geo_type');
			$table->mediumText('geo_address');
			$table->mediumText('geo_lat');
			$table->mediumText('geo_lng');
			$table->mediumText('status');
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
