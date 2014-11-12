<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveConfigToDatasourcesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    $configs = DB::table('data_source_configs')->get();
    
    Schema::table('datasources', function(Blueprint $table)
    {
      //
      $table->mediumText('columns')->after('url')->nullable();
      $table->mediumText('config')->after('columns')->nullable();
      $table->integer('config_status')->after('config')->default(0);
    });
    
    Schema::drop('data_source_configs');

    foreach ($configs as $config) {
      DB::table('datasources')
        ->where('id', $config->datasource_id)
        ->update(array(
            'columns' => $config->data_source_columns,
            'config' => $config->config,
            'config_status' => $config->config_status
          ));
    }

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    $datasources = DB::table('datasources')->get();
    
    Schema::table('datasources', function(Blueprint $table)
    {
      //
      $table->dropColumn(array('columns', 'config', 'config_status'));
    });
    
    Schema::create('data_source_configs', function(Blueprint $table)
    {
      $table->increments('id');
      $table->integer('datasource_id');
      $table->mediumText('data_source_columns')->nullable();
      $table->mediumText('config')->nullable();
      $table->integer('config_status')->default(0);
      $table->timestamps();
    });

    foreach ($datasources as $datasource) {
      DB::table('data_source_configs')->insert(
        array(
          'datasource_id' => $datasource->id,
          'data_source_columns' => $datasource->columns,
          'config' => $datasource->config,
          'config_status' => $datasource->config_status
        )
      );
    }
  }

}
