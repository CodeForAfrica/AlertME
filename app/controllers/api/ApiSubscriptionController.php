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
		$data = array(
      'subscription' => 'subscription',
      'confirm_token' => '1234567890abcdef',
      'confirm_link' => link_to('subscription/confirm/'.'1234567890abcdef', 'link', null, true),
      'confirm_url' => secure_asset('subscription/confirm/'.'1234567890abcdef')
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
		$confirm_token = md5(Input::get('bounds').Input::get('email'));
		$bounds = explode(",", Input::get('bounds'));
		
		// Validate data
		$data = Input::all();
		$data = array_add($data , 'confirm_token', $confirm_token);
		$rules = array(
			'email' => 'required|email',
			'bounds' => 'required',
			'confirm_token' => 'unique:subscriptions'
		);
		$messages = array(
			'email.required' => 'NO_EMAIL',
			'email.email' => 'NOT_EMAIL',
			'bounds.required' => 'NO_BOUNDS',
			'confirm_token.unique' => 'DUPLICATE',
		);
    $validator = Validator::make($data, $rules, $messages);
    if ($validator->fails())
    {
      return Response::json(
      	array(
					'error' => true,
					'validator' => $validator->messages()
				),
				200
			);
    }
		if ( count($bounds) != 4 ){
			return Response::json(array(
				'error' => true,
				'validator' => 'BOUNDS_ERROR'),
				200
			);
		}

		// Create User or First
		User::firstOrCreate(array('email' => Input::get('email')));
		$user = User::where('email', Input::get('email'))->first();

		// Subscription
		$subscription = new Subscription;
		$subscription->user_id = $user->id;
		$subscription->sw_lat = $bounds[0];
		$subscription->sw_lng = $bounds[1];
		$subscription->ne_lat = $bounds[2];
		$subscription->ne_lng = $bounds[3];
		$subscription->confirm_token = $confirm_token;
		$subscription->save();

		$user->increment('subscriptions');

		return Response::json(array(
				'error' => false,
				'subscription' => $subscription->toArray(),
				'status' => 'OK'
			),
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