<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForceConfigurationOfDatasources extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		$datasources = DB::table('data_sources')->where('config_status', 1)->get();
		foreach ($datasources as $datasource) {
			DB::table('data_sources')
				->where('id', $datasource->id)
      	->update(array('config_status' => 2, 'config' => ''));
		}
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
