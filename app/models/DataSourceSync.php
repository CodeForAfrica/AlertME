<?php

class DataSourceSync extends Eloquent {

    protected $table = 'data_source_syncs';

    public static function boot()
    {
      parent::boot();

      // Setup event bindings...
      DataSourceSync::created(function($datasource)
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

}
