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

  public function datasourcedata()
  {
    return $this->belongsTo('DataSourceData');
  }

  public function categories()
  {
    return $this->belongsToMany('Category', 'project_category');
  }

  public function geocode()
  {
    if (trim($this->geo_address) == '') return array( 'lat' => 0 , 'lng' => 0 );
    return $this->hasOne('Geocode', 'address', 'geo_address');
  }


  // Other functions

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

  function assignCategory($category)
  {
    $this->categories()->detach($category->id);

    $assign_cat = false;

    $keywords = explode(",", $category->keywords);

    foreach ( $keywords as $keyword ) {
      $in_title  = stripos( $this->title, $keyword );
      $in_desc   = stripos( $this->description, $keyword );
      $in_sector = stripos( $this->status, $keyword );

      // If keyword found
      if ($in_title !== false || $in_desc !== false || $in_sector !== false) {
        $assign_cat = true;
      }
    }

    if ($assign_cat) {
      $this->categories()->attach($category->id);
    }
  }

}
