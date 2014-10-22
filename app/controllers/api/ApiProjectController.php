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
    $projects = array();
    $projects_categories = array();
    $sel_cols_projects = array('projects.id', 'projects.project_id',
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

    return Response::json(array(
        'error' => false,
        'projects' => $projects,
        'projects_categories' => $projects_categories
      )
    );
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
