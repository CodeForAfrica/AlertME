<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeocodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('geocodes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->mediumText('address');
			$table->string('geo_api_id')->nullable();
			$table->longText('api_response')->nullable();
			$table->string('lat')->nullable();
			$table->string('lng')->nullable();
			$table->integer('status');
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
		Schema::drop('geocodes');
	}

}
