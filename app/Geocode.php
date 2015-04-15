<?php namespace Greenalert;

use Illuminate\Database\Eloquent\Model;

class Geocode extends Model {

    protected $table = 'geocodes';
    protected $fillable = array('address');

    public static function boot()
    {
        parent::boot();

        // Setup event bindings...
        Geocode::creating(function ($geocode) {
            if (trim($geocode->address) == '') return false;
            return $geocode;
        });

        Geocode::created(function ($geocode) {
            $geocode = Geocode::find($geocode->id);
            $geocode->fetchGeo();
            $geocode->save();

            return $geocode;
        });

    }

    public function geoapi()
    {
        return $this->belongsTo('Greenalert\GeoApi');
    }

    public function fetchGeo()
    {
        $geoapi = GeoApi::find(1);
        $this->geo_api_id = 1;

        $url = 'https://maps.googleapis.com/maps/api/geocode/json?' .
            'address=' . str_replace(" ", "+", $this->address) .
            '&region=za' .
            '&key=' . $geoapi->key;
        $api_response = json_decode(file_get_contents($url), true);

        $this->api_response = json_encode($api_response);

        if ($api_response != 0) {
            // Geocode successful
            if ($api_response['status'] == "OK") {
                $this->lat = $api_response['results'][0]['geometry']['location']['lat'];
                $this->lng = $api_response['results'][0]['geometry']['location']['lng'];
                $this->status = 1;
            }
            // Geocode over limit
            if ($api_response['status'] == "OVER_QUERY_LIMIT") {
                $this->status = 0;
            }
            // Geocode no results
            if ($api_response['status'] == "ZERO_RESULTS") {
                $this->status = 2;
            }
        }

        return $this;
    }

}
