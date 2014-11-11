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

    function datasourcedata_single()
    {
      return json_decode(DB::table('data_source_datas_'.$this->data_source_id)
        ->where('data_id', $this->project_id)->first()->data);
    }

    function categories()
    {
      return $this->belongsToMany('Category', 'project_category');
    }

    function geocode()
    {
      if (trim($this->geo_address) == '') return array( 'lat' => 0 , 'lng' => 0 );
      return $this->hasOne('Geocode', 'address', 'geo_address');
    }

    function geo()
    {
      $geo = new stdClass(); $geo->lat = 450; $geo->lng = 450;
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

    function  geojson()
    {
      $geo = $this->geo();
      $geojson = array(
        'type' => 'FeatureCollection',
        'features' => array(
          array(
            'type' => 'Feature',
            'geometry' => array('type' => 'Point', 'coordinates' => array($geo->lng , $geo->lat)),
            'properties' => array('prop0' => 'value0')
          )
        )
      );
      return json_encode($geojson);
    }

}
