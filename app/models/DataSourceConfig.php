<?php

class DataSourceConfig extends Eloquent {

    protected $table = 'data_sources_config';

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

        Queue::push('MyQueue@fetchDataSourceColumns', array('config_id' => $config->id));
      });
    }

    function datasource()
    {
      return $this->belongsTo('DataSource');
    }

}
