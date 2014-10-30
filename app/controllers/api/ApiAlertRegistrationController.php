<?php

class ApiAlertRegistrationController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		
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
		AlertUser::firstOrCreate(array('email' => Input::get('email')));
		$alert_user = AlertUser::where('email', Input::get('email'))->first();
		// if ( $alert_user->alerts > 4 ) {
		// 	return Response::json(array(
		// 		'error' => true,
		// 		'status' => 'OVER_LIMIT'),
		// 		200
		// 	);
		// }

		$bounds = explode(",", Input::get('bounds'));
		if ( count($bounds) != 4 ){
			return Response::json(array(
				'error' => true,
				'status' => 'BOUNDS_ERROR'),
				200
			);
		}

		$alert = new AlertRegistration;
		$alert->alert_user_id = $alert_user->id;
		$alert->sw_lat = $bounds[0];
		$alert->sw_lng = $bounds[1];
		$alert->ne_lat = $bounds[2];
		$alert->ne_lng = $bounds[3];
		$alert->save();
		$alert_user->increment('alerts');

		return Response::json(array(
			'error' => false,
			'alert' => $alert->toArray(),
			'status' => 'OK'),
			200
		);
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
