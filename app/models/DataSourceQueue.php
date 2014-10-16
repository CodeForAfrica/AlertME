<?php

class DataSourceQueue {

  public function fire($job, $data){

  }

  function fetchDataSourceColumns($job, $data)
  {
    Log::info('['.$job->getJobId().':'.$job->attempts().'] Fetch data columns started.');

    $config = DataSourceConfig::find($data['config_id']);
    if (!$config){
      $job->delete();
      return;
    }

    if($job->attempts() > 3){
      $config->config_status = 0;
      $config->save();
      $job->delete();
      return;
    }

    // Get data
    $data = DataSourceData::firstOrCreate( array(
      'data_source_id' => $config->data_source_id
    ));

    if ( !$data ) {
      $config->config_status = 0;
      $config->save();
    } else {
      $config->data_source_columns = $data->headers;
      $config->config_status = 2;
      $config->save();
    }

    Log::info('['.$job->getJobId().':'.$job->attempts().'] Fetch data columns finished.');

    $job->delete();

  }

}
