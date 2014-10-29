<?php

class ApiSubscriptionController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$subscriptions = Subscription::all();
		return Response::json(array(
			'error' => false,
			'subscriptions' => $subscriptions->toArray(),
			'status' => 'OK'),
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
		$subscription = Subscription::first();
		$user = DB::table('users')->where('id', $subscription->user_id)->first();
		$data = array(
      'subscription' => $subscription,
      'user' => $user,
      'confirm_token' => $subscription->confirm_token,
      'confirm_link' => link_to('subscription/confirm/'.$subscription->confirm_token, 'link', null, true),
      'confirm_url' => secure_asset('subscription/confirm/'.$subscription->confirm_token)
    );
    $view = View::make('emails.subscription.new', $data);

		if (Input::get('inline', 0) == 1) {
			Inliner::setOption('preserve_styles', true);
	    return Inliner::inline($view);
		}
		return $view;
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
		// Create User or First
		User::firstOrCreate(array('email' => Input::get('email')));
		$user = User::where('email', Input::get('email'))->first();

		// Get bounds passed
		$bounds = explode(",", Input::get('bounds'));
		if ( count($bounds) != 4 ){
			return Response::json(array(
				'error' => true,
				'status' => 'BOUNDS_ERROR'),
				200
			);
		}


		// Subscription
		$subscription = new Subscription;
		$subscription->user_id = $user->id;
		$subscription->sw_lat = $bounds[0];
		$subscription->sw_lng = $bounds[1];
		$subscription->ne_lat = $bounds[2];
		$subscription->ne_lng = $bounds[3];
		$subscription->confirm_token = Input::get('csfr',
			md5(serialize($bounds).Input::get('email'))
		);
		$subscription->save();

		$user->increment('subscriptions');

		return Response::json(array(
			'error' => false,
			'subscription' => $subscription->toArray(),
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
