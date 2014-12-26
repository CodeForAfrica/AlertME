<?php

class ApiProjectController extends \BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    //
    
    if ( Input::get('all') == 1 ) {
      ini_set('memory_limit', '-1');
      ini_set('max_execution_time', 0);
      if (Input::get('geo_only') == 1) {
        $projects = Project::select('id', 'geo_lat', 'geo_lng')->hasGeo()->get();
      } else {
        $projects = Project::all(array('id', 'geo_lat', 'geo_lng'));
      }
    } else {
      if (Input::get('min') == 1) {
        if (Input::get('geo_only') == 1) {
          $projects = Project::select('id', 'geo_lat', 'geo_lng')->hasGeo()->paginate(10);
        } else {
          $projects = Project::select('id', 'geo_lat', 'geo_lng')->paginate(10);
        }
      } else{
        if (Input::get('geo_only') == 1) {
          $projects = Project::hasGeo()->paginate(10);
        } else {
          $projects = Project::paginate(10);
        }
      }
    }
    
    return Response::json(array(
        'error' => false,
        'projects' => $projects->toArray()
      ),
      200
    );

    $projects = array();
    $projects_categories = array();
    $sel_cols_projects = array('projects.id', 'projects.data_id',
      'projects.title', 'projects.description', 'projects.geo_type',
      'projects.geo_address', 'projects.geo_lat', 'projects.geo_lng',
      'projects.status');
    $sel_cols_geocodes = array('geocodes.lat', 'geocodes.lng',
      'geocodes.status as geo_status');

    if ( Input::get('map', false) ) {
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
                               ->select('project_id', 'category_id')
                               ->get();
    } else {
      $projects = DB::table('projects')
                    ->leftJoin('geocodes', 'projects.geo_address', '=', 'geocodes.address')
                    ->select( array_merge($sel_cols_projects, $sel_cols_geocodes) )
      /*->take(10)*/->get();
      $projects_categories = DB::table('project_category')
                               ->select('project_id', 'category_id')
                               ->get();
    }

    $data = array(
      'error' => false,
      'projects' => $projects,
      'projects_categories' => $projects_categories
    );

    $temp_file = tempnam(sys_get_temp_dir(), 'pahali');
    file_put_contents($temp_file, json_encode($data));
    $response = Response::download(
      $temp_file,
      'pahali-projects.json',
      array(
        'Content-Type' => 'application/json'
      )
    );

    return $response;
    // return Response::json($data);
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    //
  }


  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    //
  }


  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    //
    $project = Project::find($id);

    if (!$project) {
      return Response::json(array(
          'error' => true,
          'project' => 'Not found',
        ),
        404
      );
    }

    if (Input::has('embed')) {
      $geojson = 'pin-l-circle-stroked+1abc9c('.$project->geo_lng.','.$project->geo_lat.')/'.
        $project->geo_lng.','.$project->geo_lat.'),13';

      $map_image_link = 'http://api.tiles.mapbox.com/v4/codeforafrica.ji193j10/'.
        $geojson.'/600x250.png256?'.
        'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
      $data = compact(
        'project', 'map_image_link', 'geojson'
      );
      return View::make('home.project_embed', $data);
    }

    return Response::json(array(
        'error' => false,
        'project' => $project,
      ),
      200
    );
  }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    //
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    //
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    //
  }


}
