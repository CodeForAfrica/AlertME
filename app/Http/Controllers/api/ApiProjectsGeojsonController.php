<?php namespace Greenalert\Http\Controllers\api;

use Greenalert\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ApiProjectsGeojsonController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 0);
        $per_page = 200; //Input::get('per_page', 0);
        $cat_id = $request->input('cat_id', -1);

        $bounds = explode(",", $request->input('bounds', '-37.683820326693805,-18.437924653474393,-37.683820326693805,56.2939453125'));

        $projects = \DB::table('projects')
            ->join('geocodes', 'projects.geo_address', '=', 'geocodes.address')
            ->where(function ($query) use ($bounds) {
                $query->where('projects.geo_type', '=', 'lat_lng')
                    ->where('projects.geo_lat', '>', $bounds[0])
                    ->where('projects.geo_lat', '<', $bounds[1])
                    ->where('projects.geo_lng', '>', $bounds[2])
                    ->where('projects.geo_lng', '<', $bounds[3]);
            })
            ->orWhere(function ($query) use ($bounds) {
                $query->where('projects.geo_type', '=', 'address')
                    ->where('projects.geo_address', '<>', '')
                    ->where('geocodes.lat', '>', $bounds[0])
                    ->where('geocodes.lat', '<', $bounds[1])
                    ->where('geocodes.lng', '>', $bounds[2])
                    ->where('geocodes.lng', '<', $bounds[3]);
            })
            ->select('projects.*', 'geocodes.lat', 'geocodes.lng')
            /*->take($per_page)*/
            ->get();

        $features = array();
        for ($i = 0; $i < count($projects); $i++) {
            $geo = new \stdClass();
            $geo->lat = 0;
            $geo->lng = 0;
            if ($projects[ $i ]->geo_type == 'lat_lng') {
                $geo->lat = floatval($projects[ $i ]->geo_lat);
                $geo->lng = floatval($projects[ $i ]->geo_lng);
            }
            if ($projects[ $i ]->geo_type == 'address') {
                $geo->lat = floatval($projects[ $i ]->lat);
                $geo->lng = floatval($projects[ $i ]->lng);
            }

            $feature = array(
                'type'       => 'Feature',
                'geometry'   => array(
                    'type'        => 'Point',
                    // coordinates here are in longitude, latitude order because
                    // x, y is the standard for GeoJSON and many formats
                    'coordinates' => array(
                        $geo->lng, $geo->lat
                    )
                ),
                'properties' => array(
                    'title'         => $projects[ $i ]->title,
                    'description'   => $projects[ $i ]->description,
                    // one can customize markers by adding simplestyle properties
                    // https://www.mapbox.com/foundations/an-open-platform/#simplestyle
                    'marker-size'   => 'small',
                    'marker-color'  => '#BE9A6B',
                    'marker-symbol' => 'cafe'
                )
            );

            $features[ $i ] = $feature;

        }

        $feature_collection = array(
            'type'     => 'FeatureCollection',
            'features' => $features
        );

        return response()->json($feature_collection);
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
        //
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
