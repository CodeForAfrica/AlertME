<?php

class Geocode extends Eloquent {

    protected $table = 'geocodes';
    protected $fillable = array('address');

    public static function boot()
    {
      parent::boot();

      // Setup event bindings...
      Geocode::creating(function($geocode)
      {
        $geocode = Geocode::fetchGeo( $geocode );

        return $geocode;
      });

    }

    public function geoapi()
    {
      return $this->belongsTo('GeoApi');
    }

    public static function geocodeProjects ( $ds_id )
    {
      if (!$ds_id){
        return;
      }
      
      $addresses = DB::table('projects')->where('datasource_id', '=', $ds_id)->groupby('geo_address')->get(array('geo_address'));

      foreach ( $addresses as $address ){

        if ( trim($address->geo_address) != '' )
        {
          $geocode = Geocode::firstOrCreate( array(
            'address' => $address->geo_address
          ));

          if( $geocode->status == 0 ) {
            $geocode = Geocode::fetchGeo( $geocode );
            $geocode->save();
          }

          DB::table('projects')
            ->where('geo_address', $address->geo_address)
            ->update(array(
                'geo_lat' => $geocode->lat,
                'geo_lng' => $geocode->lng
              ));
        }
      }

    }

    public static function fetchGeo ( $geocode )
    {
      $geoapi = GeoApi::find(1);
      $geocode->geo_api_id = 1;
      $url = 'https://maps.googleapis.com/maps/api/geocode/json?'.
        'address='.str_replace(" ", "+", $geocode->address).
        '&region=za'.
        '&key='.$geoapi->key;
      $api_response = json_decode( file_get_contents($url), true );

      $geocode->api_response = json_encode( $api_response );

      if ( $api_response != 0 )
      {
        // Geocode successful
        if ( $api_response['status'] == "OK" ) {
          $geocode->lat = $api_response['results'][0]['geometry']['location']['lat'];
          $geocode->lng = $api_response['results'][0]['geometry']['location']['lng'];
          $geocode->status = 1;
        }
        // Geocode over limit
        if ( $api_response['status'] == "OVER_QUERY_LIMIT" ) {
          $geocode->status = 0;
        }
        // Geocode no results
        if ( $api_response['status'] == "ZERO_RESULTS" ) {
          $geocode->status = 2;
        }

      }

      return $geocode;
    }
}
