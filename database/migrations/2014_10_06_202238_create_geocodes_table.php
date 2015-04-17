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
        Schema::create('geocodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address');
            $table->string('geo_api_id')->nullable();
            $table->longText('api_response')->nullable();
            $table->decimal('lat', 65, 10)->default(0)->nullable();
            $table->decimal('lng', 65, 10)->default(0)->nullable();
            $table->integer('status')->default('0');
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
