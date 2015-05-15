<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlertsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id');
            $table->integer('status')->default(0);
            $table->timestamps();
        });

        Schema::create('subscription_alert', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subscription_id');
            $table->integer('alert_id')->default(0);
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
        Schema::drop('alerts');
        Schema::drop('subscription_alert');
    }

}
