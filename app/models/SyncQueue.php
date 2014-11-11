<?php

class SyncQueue {

  public function fire($job, $data)
  {
    Log::info('['.$job->getJobId().':'.$job->attempts().'] Sync started.');

    if ($job->attempts() > 3)
    {
      Log::info('['.$job->getJobId().':'.$job->attempts().'] Sync failed.');
      $job->delete();

    } else {

      $sync = Sync::find($data['sync_id']);
      $ds_configs = DataSourceConfig::where('config_status', 1)->get();

      foreach ($ds_configs as $ds_config) {
        self::dataSourceSync($sync, $ds_config);
      }

      // TODO: Check if all data sources synced successfully


      $sync->sync_status = 1;
      $sync->save();

      Log::info('['.$job->getJobId().':'.$job->attempts().'] Sync completed.');

      $job->delete();

    }

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

    Log::info('Download completed.');

    if(!$csv) {
      $ds_sync->sync_status = 3;
      $ds_sync->save();
      return;
    }

    // Check column change
    if(array_keys($csv[0]) != json_decode($ds_config->data_source_columns)){
      Log::info('Columns are differnt from configuration.');

      $ds_sync->sync_status = 4;
      $ds_sync->save();

      $ds_config->data_source_columns = array_keys($csv[0]);
      $ds_config->config_status = 2;
      $ds_config->save();

      // Email column change needs configuration before sync

      return;
    }

    // Set data fetched
    self::setProjects($csv, $ds_config, $ds_sync);
    Log::info('Projects update completed.');

    // Geocode
    $config = json_decode($ds_config->config);
    if ($config->config_geo_type == 'address') {
      Geocode::geocodeProjects( $ds_config->data_source_id );
      Log::info('Geocode complete.');
    }

    $ds_data->raw = json_encode($csv);
    $ds_data->save();

    $ds_data->setData();
    Log::info('Data set complete.');

    $ds_sync->sync_status = 1;
    $ds_sync->save();
  }


  function setProjects ( $csv, $ds_config, $ds_sync )
  {
    $ds_cols = json_decode($ds_config->data_source_columns); // String Keys
    $config = json_decode($ds_config->config); // Integer position

    foreach ( $csv as $row ) {
      $project = Project::firstOrCreate( array(
        'project_id' => $row[ $ds_cols[ $config->config_id ] ]
      ));
      $project = Project::where('project_id', $row[ $ds_cols[ $config->config_id ]])->first();
      $project->data_source_id = $ds_config->data_source_id;
      $project->data_source_sync_id = $ds_sync->id;

      $row[ $ds_cols[ $config->config_title ] ] = strtolower($row[ $ds_cols[ $config->config_title ] ]);
      $row[ $ds_cols[ $config->config_desc ] ] = strtolower($row[ $ds_cols[ $config->config_desc ] ]);
      $row[ $ds_cols[ $config->config_title ] ] = ucwords($row[ $ds_cols[ $config->config_title ] ]);
      $row[ $ds_cols[ $config->config_desc ] ] = ucfirst($row[ $ds_cols[ $config->config_desc ] ]);

      if (strlen($row[ $ds_cols[ $config->config_title ] ]) == 0){
        $row[ $ds_cols[ $config->config_title ] ] = '[No Title]';
      }
      if (strlen($row[ $ds_cols[ $config->config_desc ] ]) == 0){
        $row[ $ds_cols[ $config->config_desc ] ] = '[No Description]';
      }

      if (strlen($row[ $ds_cols[ $config->config_title ] ]) > 254){
        $project->title = substr($row[ $ds_cols[ $config->config_title ] ], 0, 250);
        $project->title = $project->title . '...';
      } else {
        $project->title = $row[ $ds_cols[ $config->config_title ] ];
      }
      $project->description = $row[ $ds_cols[ $config->config_desc ] ];

      if ( $config->config_geo_type == 'address' ) {
        $project->geo_type = 'address';
        if (strlen($row[ $ds_cols[ $config->config_geo_add ] ]) > 254){
          $project->geo_address = substr($row[ $ds_cols[ $config->config_title ] ], 0, 250);
        } else {
          $project->geo_address = $row[ $ds_cols[ $config->config_geo_add ] ];
        }
      }
      if ( $config->config_geo_type == 'lat_lng' ) {
        $project->geo_type = 'lat_lng';
        $project->geo_lat = $row[ $ds_cols[ $config->config_geo_lat ] ];
        $project->geo_lng = $row[ $ds_cols[ $config->config_geo_lng ] ];
      }

      if ($project->status != $row[ $ds_cols[ $config->config_status ] ] && $project->status != null) {
        // Send Alert
        Alert::create(array('project_id' => $project->id));
      }

      $project->status = $row[ $ds_cols[ $config->config_status ] ];

      $project->save();
    }
  }

}
