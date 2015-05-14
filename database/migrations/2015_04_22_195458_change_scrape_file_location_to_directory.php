<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeScrapeFileLocationToDirectory extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('scrapes', function(Blueprint $table)
		{
            $table->dropColumn('file_location');
            $table->string('file_directory')->default('scrapes')->after('scraper_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('scrapes', function(Blueprint $table)
		{
            $table->dropColumn('file_directory');
            $table->string('file_location')->default(storage_path() . '/scrapes/')->after('scraper_id');
		});
	}

}
