<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlertRegistrationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('alert_registrations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('alert_user_id');
			$table->decimal('sw_lat', 65, 10);
			$table->decimal('sw_lng', 65, 10);
			$table->decimal('ne_lat', 65, 10);
			$table->decimal('ne_lng', 65, 10);
			$table->timestamps();
		});

		Schema::table('alert_registrations', function($table)
		{
			$table->index('alert_user_id');
			$table->index('sw_lat');
			$table->index('sw_lng');
			$table->index('ne_lat');
			$table->index('ne_lng');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('alert_registrations');
	}

}
