<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataSourceDatasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('data_source_datas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('data_source_id');
			$table->longText('headers');
			$table->longText('raw');
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
		Schema::drop('data_source_datas');
	}

}
