<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncludeSubscribersInUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			//
			$table->unique('email');
			$table->index('email');
			$table->integer('role_id')->default(0)->after('email');
			$table->integer('subscriptions')->default(0)->after('role_id');
		});
		DB::statement('ALTER TABLE `users` MODIFY `username` VARCHAR(64) NULL;');
		DB::statement('ALTER TABLE `users` MODIFY `password` VARCHAR(64) NULL;');

		$user = User::find(1);
		$user->role_id = 1;
		$user->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			//
			$table->dropUnique('users_email_unique');
			$table->dropIndex('users_email_index');
			$table->dropColumn(array('role_id', 'subscriptions'));
		});
		
	}

}
