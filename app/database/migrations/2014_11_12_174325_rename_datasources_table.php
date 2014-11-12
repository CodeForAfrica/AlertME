<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameDatasourcesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::rename('data_sources', 'datasources');
		Schema::table('data_source_configs', function($table)
		{
		  $table->renameColumn('data_source_id', 'datasource_id');
		});
		Schema::table('data_source_datas', function($table)
		{
		  $table->renameColumn('data_source_id', 'datasource_id');
		});
		Schema::table('data_source_syncs', function($table)
		{
		  $table->renameColumn('data_source_id', 'datasource_id');
		});
		Schema::table('projects', function($table)
		{
		  $table->renameColumn('data_source_id', 'datasource_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::rename('datasources', 'data_sources');
		Schema::table('data_source_configs', function($table)
		{
		  $table->renameColumn('datasource_id', 'data_source_id');
		});
		Schema::table('data_source_datas', function($table)
		{
		  $table->renameColumn('datasource_id', 'data_source_id');
		});
		Schema::table('data_source_syncs', function($table)
		{
		  $table->renameColumn('datasource_id', 'data_source_id');
		});
		Schema::table('projects', function($table)
		{
		  $table->renameColumn('datasource_id', 'data_source_id');
		});
	}

}
