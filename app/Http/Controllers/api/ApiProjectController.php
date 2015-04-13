<?php namespace Greenalert\Http\Controllers\api;

use Greenalert\Http\Requests;
use Greenalert\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ApiProjectController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (Input::get('all') == 1) {
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
            } else {
                if (Input::get('geo_only') == 1) {
                    $projects = Project::hasGeo()->paginate(10);
                } else {
                    $projects = Project::paginate(10);
                }
            }
        }

        return Response::json(array(
            'error'    => false,
            'projects' => $projects->toArray()
        ),
            200
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
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $project = Project::find($id);

        if ($id == 'random') {
            $projects = Project::all();
            $project = $projects[ mt_rand(0, count($projects) - 1) ];
        }

        if (!$project) {
            return Response::json(array(
                'error'   => true,
                'project' => 'Not found',
            ),
                404
            );
        }

        if (Input::has('embed')) {
            $geojson = 'pin-l-circle-stroked+1abc9c(' . $project->geo_lng . ',' . $project->geo_lat . ')/' .
                $project->geo_lng . ',' . $project->geo_lat . '),13';

            $map_image_link = 'http://api.tiles.mapbox.com/v4/codeforafrica.ji193j10/' .
                $geojson . '/600x250.png256?' .
                'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
            $data = compact(
                'project', 'map_image_link', 'geojson'
            );

            return View::make('home.project_embed', $data);
        }

        return Response::json(array(
            'error'   => false,
            'project' => $project,
        ),
            200
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
