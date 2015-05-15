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
        Schema::table('datasources', function (Blueprint $table) {
            //
            $table->mediumText('columns')->after('url')->nullable();
            $table->mediumText('config')->after('columns')->nullable();
            $table->integer('config_status')->after('config')->default(3);
        });

        if (Schema::hasTable('data_source_configs')) {
            //
            $configs = DB::table('data_source_configs')->get();

            Schema::drop('data_source_configs');

            foreach ($configs as $config) {
                DB::table('datasources')
                    ->where('id', $config->data_source_id)
                    ->update(array(
                        'columns'       => $config->data_source_columns,
                        'config'        => $config->config,
                        'config_status' => $config->config_status
                    ));
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('datasources', function ($table) {
            $table->dropColumn(['columns', 'config', 'config_status']);
        });

    }

}
