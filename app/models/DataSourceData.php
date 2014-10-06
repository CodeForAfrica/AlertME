<?php

class DataSourceData extends Eloquent {

    protected $table = 'data_source_datas';


    public static function boot()
    {
      parent::boot();

      // Setup event bindings...
      DataSourceData::creating(function($data)
      {

      });

      DataSourceData::created(function($data)
      {

      });
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
