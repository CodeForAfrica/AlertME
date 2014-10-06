<?php

class SyncQueue {

  public function fire($job, $data)
  {
    $sync = Sync::find($data['sync_id']);
    $ds_configs = DataSourceConfig::where('config_status', 1)->get();

    foreach ($ds_configs as $ds_config) {
      self::dataSourceSync($sync, $ds_config);
    }

    // Check if all data sources synced successfully


    $sync->sync_status = 1;
    $sync->save();

    $job->delete();
  }

  function dataSourceSync( $sync, $ds_config )
  {
    $ds_sync = new DataSourceSync;
    $ds_sync->sync_id = $sync->id;
    $ds_sync->data_source_id = $ds_config->data_source_id;
    $ds_sync->sync_status = 2;
    $ds_sync->save();

    // Fetch Data
    $csv = self::fetchData($ds_sync);
    if(!$csv) {
      $ds_sync->sync_status = 0;
      $ds_sync->save();
      return;
    }

    // Check column change
    if($csv[0] != json_decode($ds_config->data_source_columns)){
      $ds_sync->sync_status = 0;
      $ds_sync->save();
      return;
    }


    $ds_sync->sync_status = 1;
    $ds_sync->save();
  }

  function fetchData( $ds_sync )
  {
    $datasource = DataSource::find( $ds_sync->data_source_id );

    if(!filter_var($datasource->url, FILTER_VALIDATE_URL))
    {
      // Not a URL
      if (! file_exists ( $datasource->url)) return false;
    }
    else
    {
      // Is a URL

      $file_headers = @get_headers($datasource->url);

      if($file_headers[0] == 'HTTP/1.0 404 Not Found'){
        // echo "The file $filename does not exist";
        return false;
      } else if ($file_headers[0] == 'HTTP/1.0 302 Found' && $file_headers[7] == 'HTTP/1.0 404 Not Found'){
        // echo "The file $filename does not exist, and I got redirected to a custom 404 page..";
        return false;
      }
    }

    $csv = array_map('str_getcsv', file($datasource->url));

    return $csv;
  }

}
