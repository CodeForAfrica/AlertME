<?php

class Category extends Eloquent {

  protected $table = 'categories';

  public static function boot()
  {
    parent::boot();

    // Setup event bindings...
    Category::creating(function($category)
    {

    });

    Category::saving(function($category)
    {

    });

    Category::created(function($category)
    {
      Queue::push('CategoryQueue', array(
        'cat_id' => $category->id
      ));
    });

    Category::updated(function($category)
    {
      Queue::push('CategoryQueue', array(
        'cat_id' => $category->id
      ));
    });

    Category::deleted(function($category)
    {
      DB::table('project_category')
        ->where('category_id', $category->id)
        ->delete();
    });
  }


  // Relations

  public function projects()
  {
    return $this->belongsToMany('Project');
  }


  // Other Functions

  function keywordAssign ()
  {
    $keywords = explode(",", $this->keywords);

    $projects = Project::all();

    foreach ($projects as $project)
    {
      //
      $project->categories()->detach($this->id);

      $assign_cat = false;

      foreach ( $keywords as $keyword ) {
        $in_title  = stripos( $project->title, $keyword );
        $in_desc   = stripos( $project->description, $keyword );
        $in_sector = stripos( $project->status, $keyword );

        // If keyword found
        if ($in_title !== false || $in_desc !== false || $in_sector !== false) {
          $assign_cat = true;
        }
      }

      if ($assign_cat) {
        $project->categories()->attach($this->id);
      }
    }
  }

  static function geocoded()
  {
    $sel_cols_projects = array('projects.id', 'projects.project_id',
      'projects.title', 'projects.description', 'projects.geo_type',
      'projects.geo_address', 'projects.geo_lat', 'projects.geo_lng',
      'projects.status');
    $sel_cols_geocodes = array('geocodes.lat', 'geocodes.lng',
      'geocodes.status as geo_status');
    $projects_lat_lng = DB::table('projects')
                          ->where('geo_type', 'lat_lng')
                          ->select( $sel_cols_projects )->get();
    $projects_address = DB::table('projects')
                          ->join('geocodes', 'projects.geo_address', '=', 'geocodes.address')
                          ->where('projects.geo_type', '=', 'address')
                          ->select( array_merge($sel_cols_projects, $sel_cols_geocodes) )->get();
    $projects = array_merge($projects_lat_lng, $projects_address);

    $projects_id = array();
    foreach ($projects as $project) {
      array_push( $projects_id, $project->id);
    }
    $projects_categories = DB::table('project_category')
                             ->whereIn('project_id', $projects_id)
                             ->select('category_id')
                             ->distinct()
                             ->get();
    $category_ids = array();
    foreach ($projects_categories as $projects_category) {
      array_push( $category_ids, $projects_category->category_id);
    }
    
    if (count($category_ids) == 0) {
      return null;
    }

    $categories = Category::whereIn('id', $category_ids)->get();

    return $categories;
  }

}
