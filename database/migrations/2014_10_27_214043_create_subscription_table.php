<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->integer('project_id')->default(0)->index();
            $table->decimal('sw_lat', 65, 10)->default(450)->index();
            $table->decimal('sw_lng', 65, 10)->default(450)->index();
            $table->decimal('ne_lat', 65, 10)->default(450)->index();
            $table->decimal('ne_lng', 65, 10)->default(450)->index();
            $table->integer('status')->default(0);
            $table->string('confirm_token')->index();
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
        Schema::dropIfExists('subscriptions');
    }

}
