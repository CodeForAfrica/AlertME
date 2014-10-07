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

    function categories()
    {
      return $this->belongsToMany('Category');
    }

    function geocode()
    {
      if (trim($this->geo_address) == '') return array( 'lat' => 0 , 'lng' => 0 );
      return $this->hasOne('Geocode', 'address', 'geo_address');
    }

    function geo()
    {
      $geo = new stdClass(); $geo->lat = 0; $geo->lng = 0;
      if($this->geo_type == 'lat_lng') {
        $geo->lat = floatval ($this->geo_lat);
        $geo->lng = floatval ($this->geo_lng);
      }
      if($this->geo_type == 'address' && trim($this->geo_address) != '') {
        $geocode = Geocode::where('address', $this->geo_address)->first();
        $geo->lat = floatval ($geocode->lat);
        $geo->lng = floatval ($geocode->lng);
      }
      return $geo;
    }

}
