<?php

class DataSourceSync extends Eloquent {

    protected $table = 'data_source_syncs';

    /**
     * SYNC STATUS
     * -------------
     * 0: Failed
     * 1: Successful
     * 2: Started
     */

    public static function boot()
    {
      parent::boot();

      // Setup event bindings...
      DataSourceSync::created(function($ds_sync)
      {

      });
    }

    function sync()
    {
      return $this->belongsTo('Sync');
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
