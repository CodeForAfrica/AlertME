<?php

class DataSourceQueue {

  public function fire($job, $data){

  }

  function fetchColumns($job, $data)
  {
    Log::info('['.$job->getJobId().':'.$job->attempts().'] Fetch datasource columns started.');

    $datasource = DataSource::find($data['id']);

    if (!$datasource){
      $job->delete();
      return;
    }
    if($job->attempts() > 3){
      $datasource->config_status = 0;
      $datasource->save();

      Log::info('['.$job->getJobId().':'.$job->attempts().'] Fetch datasource columns failed.');
      $job->delete();
      return;
    }

    // Get DataSource data
    $datasourcedata = DataSourceData::firstOrCreate( array(
      'data_source_id' => $datasource->id
    ));

    $ds_data = $datasourcedata->fetch();

    if(!$ds_data) {
      $datasource->config_status = 0;
      $datasource->save();
    } else {
      $datasourcedata->headers = array_keys($ds_data[0]);
      $datasourcedata->raw = $ds_data;
      $datasourcedata->save();

      $datasource->columns = $datasourcedata->headers;
      $datasource->config_status = 2;
      $datasource->save();
    }

    Log::info('['.$job->getJobId().':'.$job->attempts().'] Fetch data columns finished.');

    $job->delete();

  }

}
