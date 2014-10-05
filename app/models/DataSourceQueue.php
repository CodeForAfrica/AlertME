<?php

class DataSourceQueue {

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

    if(!filter_var($datasource->url, FILTER_VALIDATE_URL))
    {
      // Not a URL
      
      if (! file_exists ( $datasource->url)){
        $config->config_status = 0;
        $config->save();
        $job->delete();
        return;
      }
    }
    else
    {
      // Is a URL

      $file_headers = @get_headers($datasource->url);

      if($file_headers[0] == 'HTTP/1.0 404 Not Found'){
        // echo "The file $filename does not exist";
        $config->config_status = 0;
        $config->save();
        $job->delete();
        return;
      } else if ($file_headers[0] == 'HTTP/1.0 302 Found' && $file_headers[7] == 'HTTP/1.0 404 Not Found'){
        // echo "The file $filename does not exist, and I got redirected to a custom 404 page..";
        $config->config_status = 0;
        $config->save();
        $job->delete();
        return;
      }
    }




    $csv = array_map('str_getcsv', file($datasource->url));

    $config->data_source_columns = json_encode($csv[0]);
    $config->config_status = 2;
    $config->save();

    $job->delete();

  }

  function syncDataSources ( $job, $data )
  {

    $job->delete();
  }

}
