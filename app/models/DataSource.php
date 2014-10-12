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
        DataSourceSync::where('data_source_id', '=', $datasource->id)->delete();

        DataSourceData::where('data_source_id', '=', $datasource->id)->delete();
        Schema::dropIfExists('data_source_datas_'.$datasource->id);

        Project::where('data_source_id', '=', $datasource->id)->delete();
      });
    }

    public function datasourceconfig()
    {
      return $this->hasOne('DataSourceConfig');
    }

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

}
