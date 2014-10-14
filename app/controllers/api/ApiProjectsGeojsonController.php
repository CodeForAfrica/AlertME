<?php

class ApiProjectsGeojsonController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$page = Input::get('page', 0);
		$per_page = 200; //Input::get('per_page', 0);
		$cat_id = Input::get('cat_id', -1);

		$GLOBALS['bounds'] = explode(",", Input::get('bounds', '-33.515064400105665,-32.940691418651056,20.8685302734375,22.846069335937496'));

		$projects = DB::table('projects')
	    ->join('geocodes', 'projects.geo_address', '=', 'geocodes.address')
			->where(function($query) {
				$bounds = $GLOBALS['bounds'];
				$query->where('projects.geo_type','=','lat_lng')
					->where('projects.geo_lat', '>', $bounds[0])
					->where('projects.geo_lat', '<', $bounds[1])
					->where('projects.geo_lng', '>', $bounds[2])
					->where('projects.geo_lng', '<', $bounds[3]);
			})
			->orWhere(function($query)
				{
					$bounds = $GLOBALS['bounds'];
					$query->where('projects.geo_type', '=', 'address')
						->where('projects.geo_address', '<>', '')
						->where('geocodes.lat', '>', $bounds[0])
						->where('geocodes.lat', '<', $bounds[1])
						->where('geocodes.lng', '>', $bounds[2])
						->where('geocodes.lng', '<', $bounds[3]);
				})
			->select('projects.*', 'geocodes.lat', 'geocodes.lng')
	    /*->take($per_page)*/->get();


		$features = array();
		for( $i = 0; $i < count($projects) ; $i++ )
		{
			$geo = new stdClass(); $geo->lat = 0; $geo->lng = 0;
			if($projects[$i]->geo_type == 'lat_lng') {
				$geo->lat = floatval ($projects[$i]->geo_lat);
				$geo->lng = floatval ($projects[$i]->geo_lng);
			}
			if($projects[$i]->geo_type == 'address') {
				$geo->lat = floatval ($projects[$i]->lat);
				$geo->lng = floatval ($projects[$i]->lng);
			}

			$feature = array(
				'type'       => 'Feature',
				'geometry'   => array(
					'type' => 'Point',
					// coordinates here are in longitude, latitude order because
					// x, y is the standard for GeoJSON and many formats
					'coordinates' => array(
						$geo->lng, $geo->lat
					)
				),
				'properties' => array(
					'title'         => $projects[$i]->title,
					'description'   => $projects[$i]->description,
					// one can customize markers by adding simplestyle properties
					// https://www.mapbox.com/foundations/an-open-platform/#simplestyle
					'marker-size'   => 'small',
					'marker-color'  => '#BE9A6B',
					'marker-symbol' => 'cafe'
				)
			);

			$features[$i] = $feature;

		}


		$feature_collection = array(
			'type' => 'FeatureCollection',
			'features' => $features
		);

		return Response::json($feature_collection);

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


}
