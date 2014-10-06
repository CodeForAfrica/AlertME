<?php

class SyncQueue {

  public function fire($job, $data)
  {
    if ($job->attempts() > 3)
    {
        $job->delete();
    }

    $sync = Sync::find($data['sync_id']);
    $ds_configs = DataSourceConfig::where('config_status', 1)->get();

    foreach ($ds_configs as $ds_config) {
      self::dataSourceSync($sync, $ds_config);
    }

    // Check if all data sources synced successfully


    $sync->sync_status = 1;
    $sync->save();

    Log::info('Sync completed.');

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
    $ds_data = DataSource::find($ds_sync->data_source_id)->datasourcedata;
    $csv = $ds_data->fetchData();

    if(!$csv) {
      $ds_sync->sync_status = 3;
      $ds_sync->save();
      return;
    }

    // Check column change
    if(array_keys($csv[0]) != json_decode($ds_config->data_source_columns)){
      $ds_sync->sync_status = 4;
      $ds_sync->save();
      return;
    }

    // Set data fetched
    self::setProjects($csv, $ds_config, $ds_sync);

    $ds_data->raw = json_encode($csv);
    $ds_data->save();


    $ds_sync->sync_status = 1;
    $ds_sync->save();
  }


  function setProjects ( $csv, $ds_config, $ds_sync )
  {
    $ds_cols = json_decode($ds_config->data_source_columns);
    $config = json_decode($ds_config->config);


    foreach ( $csv as $row ) {
      $project = Project::firstOrCreate( array(
        'project_id' => $row[ $ds_cols[ $config->config_id ] ]
      ));
      $project->data_source_id = $ds_config->data_source_id;
      $project->data_source_sync_id = $ds_sync->id;
      $project->title = $row[ $ds_cols[ $config->config_title ] ];
      $project->description = $row[ $ds_cols[ $config->config_desc ] ];
      if ( $config->config_geo_type == 'address' ) {
        $project->geo_type = 'address';
        $project->geo_address = $row[ $ds_cols[ $config->config_geo_add ] ];
      }
      if ( $config->config_geo_type == 'lat_lng' ) {
        $project->geo_type = 'lat_lng';
        $project->geo_lat = $row[ $ds_cols[ $config->config_geo_lat ] ];
        $project->geo_lng = $row[ $ds_cols[ $config->config_geo_lng ] ];
      }
      $project->status = $row[ $ds_cols[ $config->config_status ] ];
      $project->save();
    }
  }

}
