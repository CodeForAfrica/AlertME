<?php

class GeoApi extends Eloquent {

    protected $table = 'geo_apis';

    public static function boot()
    {
      parent::boot();

      // Setup event bindings...
      GeoApi::created(function($project)
      {

      });

    }

    public function geocodes()
    {
      return $this->hasMany('Geocode');
    }
}
