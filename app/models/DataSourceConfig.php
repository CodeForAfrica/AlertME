<?php

class DataSourceConfig extends Eloquent {

    protected $table = 'data_sources_config';

    public static function boot()
    {
      parent::boot();

      // Setup event bindings...
      DataSourceConfig::created(function($config)
      {
        //Fetch columns

      });
    }

    function dataSource()
    {
      return $this->belongsTo('DataSource');
    }

}
