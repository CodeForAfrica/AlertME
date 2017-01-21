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
        Schema::create('geo_apis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('url')->nullable();
            $table->string('key')->nullable();
            $table->timestamps();
        });

        // Add first geoapi
        $geoapi = new \Greenalert\GeoApi();
        $geoapi->name = 'Google Geocoding API';
        $geoapi->url = 'https://developers.google.com/maps/documentation/geocoding/';
        $geoapi->save();

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
