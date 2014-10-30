<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlertUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('alert_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('email');
			$table->integer('alerts')->default(0);
			$table->timestamps();
		});

		Schema::table('alert_users', function($table)
		{
			$table->index('email');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('alert_users');
	}

}
