<?php

class HomeController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Default Home Controller
  |--------------------------------------------------------------------------
  |
  | You may wish to use controllers instead of, or in addition to, Closure
  | based routes. That's great! Here is an example controller method to
  | get you started. To route to this controller, just add the route:
  |
  | Route::get('/', 'HomeController@showHome');
  |
  */

  public function showHome()
  {
    $home = Page::find(1);

    $projects = DB::table('projects')->take(10)->get();
    $projects_count = DB::table('projects')->count();

    $data = compact(
      'home','projects','projects_count'
    );
    return View::make('home.index', $data);
  }


  public function showAbout()
  {
    $about = Page::find(2);
    $data = compact(
      'about'
    );
    return View::make('home.about', $data);
  }


  public function showMap()
  {
    $projects = DB::table('projects')->take(10)->get();

    $categories = Category::geocoded();

    $data = array(
      'projects' => $projects,
      'categories' => $categories
    );
    return View::make('home.map', $data);
  }


  public function getSearch()
  {
    $q = Input::get('q');

    $projects_sql = Project::whereRaw(
      "MATCH(title, description, geo_address, status) AGAINST (? IN BOOLEAN MODE)", 
      array($q)
    );
    $projects_count = $projects_sql->count();
    $projects = $projects_sql->paginate(10);

    // Limit length
    for ($i=0; $i < count($projects); $i++) { 
      if (strlen($projects[$i]->title) > 80) {
        $projects[$i]->title = substr($projects[$i]->title, 0, 80).'...';
      }
      if (strlen($projects[$i]->description) > 200) {
        $projects[$i]->description = substr($projects[$i]->description, 0, 200).'...';
      }
    }

    $data = compact(
      'projects', 'projects_count'
    );
    return View::make('home.search', $data);
  }


  public function showProject($id)
  {
    $project = Project::findOrFail($id);

    $geojson = 'pin-l-circle-stroked+1abc9c('.$project->geo()->lng.','.$project->geo()->lat.')/'.
      $project->geo()->lng.','.$project->geo()->lat.'),13';

    $map_image_link = 'http://api.tiles.mapbox.com/v4/codeforafrica.ji193j10/'.
      $geojson.'/520x293.png256?'.
      'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';

    $data = compact(
      'project', 'map_image_link', 'geojson'
    );
    return View::make('home.project', $data);
  }

}
