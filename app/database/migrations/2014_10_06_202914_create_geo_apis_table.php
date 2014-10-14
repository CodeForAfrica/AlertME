<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeoApisTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('geo_apis', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('url')->nullable();
			$table->string('key')->nullable();
			$table->timestamps();
		});

		// Add geoapi using Eloquent
		$geo_api = new GeoApi;
		$geo_api->name = 'Google Geocoding API';
		$geo_api->url = 'https://developers.google.com/maps/documentation/geocoding/';
		$geo_api->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('geo_apis');
	}

}
