<?php

class MyQueue {

  public function fire($job, $data){

  }

  function fetchDataSourceColumns($job, $data)
  {
    $config = DataSourceConfig::find($data['config_id']);
    if (!$config){
      $job->delete();
      return;
    }

    $datasource = DataSource::find($config->data_source_id);

    if (! file_exists ( $datasource->url)){
      $config->config_status = 0;
      $config->save();
      $job->delete();
      return;
    }

    $csv = array_map('str_getcsv', file($datasource->url));

    $config->data_source_columns = json_encode($csv[0]);
    $config->config_status = 2;
    $config->save();

    $job->delete();

  }

}
