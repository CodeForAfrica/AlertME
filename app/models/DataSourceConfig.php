<?php

class DataSourceConfig extends Eloquent {

    protected $table = 'data_source_configs';

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
      DataSourceConfig::created(function($config)
      {
        // Fetch columns
        $config->config_status = 3;
        $config->save();

        Queue::push('DataSourceQueue@fetchDataSourceColumns', array('config_id' => $config->id));
      });

      DataSourceConfig::saving(function($config)
      {
        $cols = $config->data_source_columns;
        $config->data_source_columns = is_array($cols) ? json_encode($cols) : $cols;

        return $config;
      });

      DataSourceConfig::saved(function($config)
      {
        
      });
    }

    function datasource()
    {
      return $this->belongsTo('DataSource');
    }

    function projects()
    {
      return $this->hasMany('Project');
    }

}
