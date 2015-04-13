<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsDataColumn extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $datasources = DB::table('data_sources')->get();
        Schema::table('projects', function (Blueprint $table) {
            //
            $table->longText('data')->after('status')->nullable();
        });
        foreach ($datasources as $datasource) {
            if (Schema::hasTable('data_source_datas_' . $datasource->id)) {
                //
                $ds_datas = DB::table('data_source_datas_' . $datasource->id)->get();
                foreach ($ds_datas as $ds_data) {
                    DB::table('projects')
                        ->where('project_id', $ds_data->data_id)
                        ->update(array('data' => $ds_data->data));
                }
                Schema::drop('data_source_datas_' . $datasource->id);
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
        $projects = DB::table('projects')->get();
        foreach ($projects as $project) {
            if (!Schema::hasTable('data_source_datas_' . $project->datasource_id)) {
                Schema::create('data_source_datas_' . $project->datasource_id, function ($table) {
                    $table->increments('id');
                    $table->string('data_id')->default('0');
                    $table->longText('data')->nullable();
                    $table->timestamps();
                });
                Schema::table('data_source_datas_' . $project->datasource_id, function ($table) {
                    $table->index('data_id');
                });
            }
            DB::table('data_source_datas_' . $project->datasource_id)->insert(
                array(
                    'data_id' => $project->project_id,
                    'data'    => $project->data
                )
            );
        }
        Schema::table('projects', function (Blueprint $table) {
            //
            $table->dropColumn('data');
        });
    }

}
