<?php

class DataSource extends Eloquent {

    protected $table = 'data_sources';

    function dataSourceConfig()
    {
      return $this->hasOne('DataSourceConfig');
    }

}
