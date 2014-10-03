<?php

class DataSourceConfig extends Eloquent {

    protected $table = 'data_sources_config';

    DataSourceConfig::created(function($config)
    {
      //Fetch columns
      
    });

    function dataSource()
    {
      return $this->belongsTo('DataSource');
    }

}
