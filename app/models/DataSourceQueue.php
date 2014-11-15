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

    $ds_data = $datasource->fetch();

    if(!$ds_data) {
      $datasource->config_status = 0;
      $datasource->save();
    } else {
      $datasource->columns = array_keys($ds_data[0]);
      $datasource->config_status = 2;
      $datasource->save();
    }

    Log::info('['.$job->getJobId().':'.$job->attempts().'] Fetch datasource columns successful.');

    $job->delete();

  }

}
