<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataSourceSyncsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_source_syncs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sync_id');
            $table->integer('data_source_id');
            $table->integer('sync_status');
            $table->timestamp('completed_on')->default('0000-00-00 00:00:00')->nullable();
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
        Schema::drop('data_source_syncs');
    }

}
