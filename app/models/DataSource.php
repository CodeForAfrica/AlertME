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
        $config->data_source_id = $datasource->id;
        $config->config_status = 2;
        $config->save();
      });

      DataSource::deleted(function($datasource)
      {
        DataSourceConfig::where('data_source_id', '=', $datasource->id)->delete();
      });
    }

    function datasourceconfig()
    {
      return $this->hasOne('DataSourceConfig');
    }

    function datasourcesync()
    {
      return $this->hasMany('DataSourceSync');
    }

    function datasourcedata()
    {
      return $this->hasOne('DataSourceData');
    }

    function projects()
    {
      return $this->hasMany('Project');
    }

}
