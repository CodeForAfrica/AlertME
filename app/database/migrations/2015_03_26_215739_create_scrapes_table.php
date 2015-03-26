<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScrapesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('scrapes', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('scraper_id');
            $table->string('file_location');
            $table->string('file_name');
            $table->text('stats');
            $table->integer('status');
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
		Schema::drop('scrapes');
	}

}
