<?php

class Datasource extends Eloquent {

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
    Datasource::created(function($datasource)
    {
      Queue::push('DatasourceQueue@fetchColumns', array('id' => $datasource->id));
    });

    Datasource::deleting(function($datasource)
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

  public function datasourcesync()
  {
    return $this->hasMany('DataSourceSync');
  }

  public function datasourcedata()
  {
    return $this->hasOne('DataSourceData');
  }

  public function projects()
  {
    return $this->hasMany('Project');
  }


  // Other functions

  function sync( $sync )
  {
    // Data Source Config
    $ds_config = $this->datasourceconfig;

    // Add DataSource Sync
    $ds_sync = new DataSourceSync;
    $ds_sync->sync_id = $sync->id;
    $ds_sync->data_source_id = $this->id;
    if (Schema::hasTable('data_source_datas_'.$this->id)) {
      $ds_sync->sync_status = 2; // Old Data Source Sync
    } else {
      $ds_sync->sync_status = 3; // New Data Source Sync
    }
    $ds_sync->save();

    // Fetch Data
    $csv = $this->fetchData();
    Log::info('Download completed.');

    if(!$csv) {
      $ds_sync->sync_status = 3;
      $ds_sync->save();
      return false;
    }

    // Check column change
    if(array_keys($csv[0]) != json_decode($ds_config->data_source_columns)){
      Log::info('Columns are differnt from configuration.');

      $ds_sync->sync_status = 4;
      $ds_sync->save();

      $ds_config->data_source_columns = array_keys($csv[0]);
      $ds_config->config_status = 2;
      $ds_config->save();

      // TODO: Email column change - needs configuration before sync

      return false;
    }

    // Set data fetched
    $this->setProjects($csv, $ds_config, $ds_sync);
    Log::info('Projects update completed.');

    // Geocode
    $config = json_decode($ds_config->config);
    if ($config->config_geo_type == 'address') {
      Geocode::geocodeProjects( $this->id );
      Log::info('Geocode complete.');
    }
    
    $ds_data = $this->datasourcedata;
    $ds_data->raw = json_encode($csv);
    $ds_data->save();

    $ds_data->setData();
    Log::info('Data set complete.');
    
    $ds_sync->sync_status = 1;
    $ds_sync->save();

    return true;
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
  

  public function fetchData()
  {
    // Validate URL + Headers
    if(!filter_var($this->url, FILTER_VALIDATE_URL))
    {
      // Not a Valid URL
      return false;
    } else {
      // Is a Valid URL
      $file_headers = @get_headers($this->url);

      if($file_headers[0] == 'HTTP/1.0 404 Not Found'){
        // echo "The file $filename does not exist";
        return false;
      } else if ($file_headers[0] == 'HTTP/1.0 302 Found' && $file_headers[7] == 'HTTP/1.0 404 Not Found'){
        // echo "The file $filename does not exist, and I got redirected to a custom 404 page..";
        return false;
      }
    }

    // Validate File Exists
    if (! file_exists ( $this->url)) return false;

    // Return array of csv
    return $this->csv_to_array($this->url);
  }


  /**
   * Convert a comma separated file into an associated array.
   * The first row should contain the array keys.
   *
   * Example:
   *
   * @param string $filename Path to the CSV file
   * @param string $delimiter The separator used in the file
   * @return array
   * @link http://gist.github.com/385876
   * @author Jay Williams <http://myd3.com/>
   * @copyright Copyright (c) 2010, Jay Williams
   * @license http://www.opensource.org/licenses/mit-license.php MIT License
   */
  function csv_to_array($filename='', $delimiter=',')
  {

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
      while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE)
      {
        $row = array_map('trim', $row);
        if(!$header)
          $header = $row;
        else
          $data[] = array_combine($header, $row);
      }
      fclose($handle);
    }
    return $data;
  }

}
