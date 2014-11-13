<?php

class DataSource extends Eloquent {

  /**
   * CONFIG STATUS
   * -------------
   * 0: Failed to configure
   * 1: Configured successfully
   * 2: Ready to configure
   * 3: Fetching columns
   * 4:
   */

  public static function boot()
  {
    parent::boot();

    // Setup event bindings...
    DataSource::created(function($datasource)
    {
      Queue::push('DataSourceQueue@fetchColumns', array('id' => $datasource->id));
    });

    DataSource::deleting(function($datasource)
    {
      DataSourceSync::where('datasource_id', '=', $datasource->id)->delete();

      DataSourceData::where('datasource_id', '=', $datasource->id)->delete();
      Schema::dropIfExists('data_source_datas_'.$datasource->id);

      Project::where('datasource_id', '=', $datasource->id)->delete();
    });
  }


  // Accessors & Mutators

  public function getConfigAttribute($value)
  {
    return json_decode($value);
  }

  public function setConfigAttribute($value)
  {
    $this->attributes['config'] = json_encode($value);
  }

  public function getColumnsAttribute($value)
  {
    return json_decode($value);
  }

  public function setColumnsAttribute($value)
  {
    $this->attributes['columns'] = json_encode($value);
  }


  // Relationships

  public function datasourcedata()
  {
    return $this->hasOne('DataSourceData', 'datasource_id');
  }

  public function datasourcesync()
  {
    return $this->hasMany('DataSourceSync', 'datasource_id');
  }

  public function projects()
  {
    return $this->hasMany('Project', 'datasource_id');
  }


  // Other functions

  function sync( $sync )
  {
    // Add DataSource Sync
    $ds_sync = new DataSourceSync;
    $ds_sync->sync_id = $sync->id;
    $ds_sync->datasource_id = $this->id;
    if (Schema::hasTable('data_source_datas_'.$this->id)) {
      $ds_sync->sync_status = 2; // Old Data Source Sync
    } else {
      $ds_sync->sync_status = 3; // New Data Source Sync
    }
    $ds_sync->save();

    // Fetch Data
    $csv = $this->datasourcedata->fetch();
    Log::info('Download completed.');
    if(!$csv) {
      $ds_sync->sync_status = 3;
      $ds_sync->save();
      return false;
    }

    // Check column change
    if(array_keys($csv[0]) != $this->columns){
      Log::info('Columns are differnt from configuration.');

      $ds_sync->sync_status = 4;
      $ds_sync->save();

      $this->columns = array_keys($csv[0]);
      $this->status->col = 2;
      $this->save();

      // TODO: Email column change - needs configuration before sync

      return false;
    }

    // Set Projects from data fetched
    $this->setProjects($csv, $ds_sync);
    Log::info('Projects update completed.');

    // Geocode
    if ($this->config->geo->type == 'address') {
      Geocode::geocodeProjects( $this->id );
      Log::info('Geocode complete.');
    }
    
    $this->datasourcedata->raw = $csv;
    $this->datasourcedata->save();

    $this->datasourcedata->setData();
    Log::info('Data set complete.');
    
    $ds_sync->sync_status = 1;
    $ds_sync->save();

    return true;
  }

  function setProjects ( $csv, $ds_sync )
  {
    $cols = $this->columns; // String Keys
    $config = $this->config; // Integer position

    foreach ( $csv as $row ) {
      $project = Project::firstOrCreate( array(
        'project_id' => $row[ $cols[ $config->id->col ] ]
      ));
      $project = Project::where('project_id', $row[ $cols[ $config->id->col ]])->first();
      $project->datasource_id = $this->id;
      $project->data_source_sync_id = $ds_sync->id;

      $row[ $cols[ $config->title->col ] ] = strtolower($row[ $cols[ $config->title->col ] ]);
      $row[ $cols[ $config->desc->col ] ] = strtolower($row[ $cols[ $config->desc->col ] ]);
      $row[ $cols[ $config->title->col ] ] = ucwords($row[ $cols[ $config->title->col ] ]);
      $row[ $cols[ $config->desc->col ] ] = ucfirst($row[ $cols[ $config->desc->col ] ]);

      if (strlen($row[ $cols[ $config->title->col ] ]) == 0){
        $row[ $cols[ $config->title->col ] ] = '[No Title]';
      }
      if (strlen($row[ $cols[ $config->desc->col ] ]) == 0){
        $row[ $cols[ $config->desc->col ] ] = '[No Description]';
      }

      if (strlen($row[ $cols[ $config->title->col ] ]) > 254){
        $project->title = substr($row[ $cols[ $config->title->col ] ], 0, 250);
        $project->title = $project->title . '...';
      } else {
        $project->title = $row[ $cols[ $config->title->col ] ];
      }
      $project->description = $row[ $cols[ $config->desc->col ] ];

      if ( $config->geo->type == 'address' ) {
        $project->geo_type = 'address';
        if (strlen($row[ $cols[ $config->geo->address->col ] ]) > 254){
          $project->geo_address = substr($row[ $cols[ $config->title->col ] ], 0, 250);
        } else {
          $project->geo_address = $row[ $cols[ $config->geo->address->col ] ];
        }
      }
      if ( $config->geo->type == 'lat_lng' ) {
        $project->geo_type = 'lat_lng';
        $project->geo_lat = $row[ $cols[ $config->geo->lat_lng->lat->col ] ];
        $project->geo_lng = $row[ $cols[ $config->geo->lat_lng->lng->col ] ];
      }

      if ($project->status != $row[ $cols[ $config->status->col ] ] && $project->status != null) {
        // Send Alert
        //Alert::create(array('project_id' => $project->id));
      }

      $project->status = $row[ $cols[ $config->status->col ] ];

      $project->save();
    }
  }

}
