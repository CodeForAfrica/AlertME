<?php

class DataSource extends Eloquent {

    protected $table = 'data_sources';

    public static function boot()
    {
      parent::boot();

      // Setup event bindings...
      DataSource::created(function($datasource)
      {
        $config = new DataSourceConfig;
        $config->datasource_id = $datasource->id;
        $config->datasource_columns = '';
        $config->config_status = 2;
        $config->save();
      });

      DataSource::deleted(function($datasource)
      {
        DataSourceConfig::where('datasource_id', '=', $datasource->id)->delete();
      });
    }

    function dataSourceConfig()
    {
      return $this->hasOne('DataSourceConfig');
    }

}
