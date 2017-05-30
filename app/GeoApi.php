<?php namespace AlertME;

use Illuminate\Database\Eloquent\Model;

class GeoApi extends Model {

    protected $table = 'geo_apis';

    public static function boot()
    {
        parent::boot();

        // Setup event bindings...
        GeoApi::created(function ($project) {

        });

    }

    public function geocodes()
    {
        return $this->hasMany('AlertME\Geocode');
    }

}
