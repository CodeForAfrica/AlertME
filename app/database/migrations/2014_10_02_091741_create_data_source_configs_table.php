<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataSourceConfigsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('data_source_configs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('data_source_id');
			$table->mediumText('data_source_columns')->nullable();
			$table->integer('config_status');
			$table->mediumText('config')->nullable();
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
		Schema::drop('data_source_configs');
	}

}
