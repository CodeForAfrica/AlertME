<?php

class Project extends Eloquent {

    protected $table = 'projects';
    protected $fillable = array('project_id');

    public static function boot()
    {
      parent::boot();

      // Setup event bindings...
      Project::created(function($project)
      {

      });

    }

    function datasource()
    {
      return $this->belongsTo('DataSource');
    }

    function datasourceconfig()
    {
      return $this->belongsTo('DataSourceConfig');
    }

    function datasourcesync()
    {
      return $this->belongsTo('DataSourceSync');
    }

    function datasourcedata()
    {
      return $this->belongsTo('DataSourceData');
    }

}
