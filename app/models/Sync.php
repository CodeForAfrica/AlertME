<?php

class Sync extends Eloquent {

    protected $table = 'syncs';

    /**
     * SYNC STATUS
     * -------------
     * 0: All data sources failed to sync
     * 1: All data sources successfully synced
     * 2: Sync Starting
     * 3: Sync Started
     * 4: Partial sync failure
     */

    public static function boot()
    {
      parent::boot();

      // Setup event bindings...
      Sync::created(function($sync)
      {

      });
    }

    function datasourcesyncs()
    {
      return $this->hasMany('DataSourceSync');
    }

    function user()
    {
      return $this->belongsTo('User');
    }

}
