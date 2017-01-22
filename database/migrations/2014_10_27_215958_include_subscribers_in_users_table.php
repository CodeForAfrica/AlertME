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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->index('email');
            $table->integer('role_id')->default(0)->after('email');
            $table->integer('subscriptions')->default(0)->after('role_id');

        });

        Schema::table('users', function ($table) {
            $table->string('username')->nullable()->change();
            $table->string('password')->nullable()->change();
        });


        // Update first user to have super admin role
        DB::table('users')
            ->where('id', 1)
            ->update(array('role_id' => 1));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropIndex('users_email_index');
            $table->dropColumn(array('role_id', 'subscriptions'));
        });

    }

}
