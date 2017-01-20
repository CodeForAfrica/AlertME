<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 64);
            $table->string('password', 64);
            $table->string('email', 128);
            $table->rememberToken();
            $table->timestamps();
        });

        // Add first user
        $user = new \Greenalert\User();
        $user->username = 'admin';
        $user->email = 'admin@localhost';
        $user->password = Hash::make('password');
        $user->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }

}
