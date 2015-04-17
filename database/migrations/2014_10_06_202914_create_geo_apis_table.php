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
        DB::table('geo_apis')->insert(
            array(
                'name' => 'Google Geocoding API',
                'url'  => 'https://developers.google.com/maps/documentation/geocoding/'
            )
        );
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
