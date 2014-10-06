<?php

class DataSourceSync extends Eloquent {

    protected $table = 'data_source_syncs';

    /**
     * SYNC STATUS
     * -------------
     * 0: Failed (Other)
     * 1: Successful
     * 2: Started
     * 3: Failed on CSV Fetch
     * 4: Columns Do Not Match (Reconfigure)
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
