<?php

class Project extends Eloquent {

  protected $table = 'projects';
  protected $fillable = array('data_id');

  public static function boot()
  {
    parent::boot();

    // Setup event bindings...
    Project::created(function($project)
    {

    });

    Project::deleting(function($project)
    {
      $project->categories()->detach();
    });

  }


  // Accessors & Mutators

  public function getTitleAttribute($value)
  {
    return $value;
  }

  public function setTitleAttribute($value)
  {
    $upper_check = preg_replace("/[^a-zA-Z]+/", "", $value);
    if (ctype_upper($upper_check)) {
      $value = strtolower($value);
      $value = ucwords($value);
    }

    if (strlen($value) == 0){
      $value = '[No Title]';
    }

    if (strlen($value) > 254){
      $value = substr($value, 0, 250);
      $value = $value . '...';
    }
    $this->attributes['title'] = $value;
  }

  public function getDescriptionAttribute($value)
  {
    return $value;
  }

  public function setDescriptionAttribute($value)
  {
    $upper_check = preg_replace("/[^a-zA-Z]+/", "", $value);
    if (ctype_upper($upper_check)) {
      $value = strtolower($value);
      $value = ucfirst($value);
    }

    if (strlen($value) == 0){
      $value = '[No Description]';
    }

    $this->attributes['description'] = $value;
  }

  public function getGeoAddressAttribute($value)
  {
    return $value;
  }

  public function setGeoAdressAttribute($value)
  {
    if (strlen($value) > 254){
      $value = substr($value, 0, 254);
    }
    $this->attributes['geo_address'] = $value;
  }

  public function getStatusAttribute($value)
  {
    return $value;
  }

  public function setStatusAttribute($value)
  {
    if ($this->status != $value) {
      // Create Alert
      // Alert::create(array('project_id' => $project->id));
    }
    $this->attributes['status'] = $value;
  }

  public function getDataAttribute($value)
  {
    return json_decode($value);
  }

  public function setDataAttribute($value)
  {
    $this->attributes['data'] = json_encode($value);
  }


  // Relations

  public function datasource()
  {
    return $this->belongsTo('DataSource');
  }

  public function datasourcesync()
  {
    return $this->belongsTo('DataSourceSync');
  }

  public function categories()
  {
    return $this->belongsToMany('Category', 'project_category');
  }

  public function subscriptions()
  {
    return $this->hasMany('Subscription');
  }

  public function geocode()
  {
    if (trim($this->geo_address) == '') return array( 'lat' => 0 , 'lng' => 0 );
    return $this->hasOne('Geocode', 'address', 'geo_address');
  }


  // Other functions

  function geo()
  {
    $project = DB::table('projects')->where('id', $this->id)->first();
    $geo = new stdClass(); $geo->lat = 450; $geo->lng = 450;
    if($project->geo_type == 'lat_lng') {
      $geo->lat = floatval ($project->geo_lat);
      $geo->lng = floatval ($project->geo_lng);
    }
    if($project->geo_type == 'address' && trim($project->geo_address) != '') {
      $geocode = Geocode::where('address', $project->geo_address)->first();
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

  function assignCategory($category)
  {
    $this->categories()->detach($category->id);

    $cols = $this->datasource->columns;
    $config = $this->datasource->config; 

    $assign_cat = false;

    $keywords = explode(",", $category->keywords);

    foreach ( $keywords as $keyword ) {
      $in_title  = stripos( $this->data->$cols[ $config->title->col ], $keyword );
      $in_desc   = stripos( $this->data->$cols[ $config->desc->col ], $keyword );

      // If keyword found
      if ($in_title !== false || $in_desc !== false ) {
        $assign_cat = true;
      }
    }

    if ($assign_cat) {
      $this->categories()->attach($category->id);
    }
  }

}
