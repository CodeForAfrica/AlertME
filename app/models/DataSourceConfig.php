<?php

class DataSourceConfig extends Eloquent {

    protected $table = 'data_sources_config';

    function dataSource()
    {
      return $this->belongsTo('DataSource');
    }

}
