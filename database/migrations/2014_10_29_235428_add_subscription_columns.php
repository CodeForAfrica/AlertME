<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubscriptionColumns extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            //
            $table->string('bounds')->index()->nullable()->after('ne_lng');
            $table->string('center')->index()->nullable()->after('bounds');
            $table->integer('zoom')->nullable()->after('center');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            //
            $table->dropIndex('subscriptions_bounds_index');
            $table->dropIndex('subscriptions_center_index');
            $table->dropColumn(array('bounds', 'center', 'zoom'));
        });
    }

}
