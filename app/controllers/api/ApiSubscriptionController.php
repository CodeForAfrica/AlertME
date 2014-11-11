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
    $subscription->bounds = Input::get('bounds');

    $subscription->center = Input::get('center');
    $subscription->zoom = Input::get('zoom');

    $subscription->geojson = Input::get('geojson');

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


  /**
   * Confirm Subscription
   *
   * @param  string  $confirm_token
   * @return View
   */
  public function confirm($confirm_token)
  {
    //
    $subscription = Subscription::where('confirm_token', $confirm_token)->firstOrFail();
    if ($subscription->status == 0) {
      $subscription->status = 1;
      $subscription->save();
      $msg_confirm = 'Confirmed';
    }

    $user = User::find($subscription->user_id);
    if (Input::has('fullname')) {
      $user->fullname = Input::get('fullname');
      $user->save();
      $msg_details = 'Updated';
    }

    $map_image_link = 'http://api.tiles.mapbox.com/v4/codeforafrica.ji193j10/'.
      $subscription->center.','.$subscription->zoom.
      '/600x400.png?'.
      'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';

    $map_link = secure_asset('map/#!/bounds='.$subscription->bounds);
    
    $data = compact(
      'msg_confirm', 'msg_details',
      'subscription', 'user',
      'map_image_link', 'map_link'
    );
    return View::make('subscriptions.confirm', $data);
  }



  public function email()
  {
    //
    switch (Input::get('type')) {
      case 'alert':
        $view_name = 'emails.subscription.alert';
        break;
      
      default:
        $view_name = 'emails.subscription.new';
        break;
    }

    // All subscriptions
    $subscription = Subscription::first();
    $user = User::find($subscription->user_id);

    // New Subscription
    $confirm_link = link_to('subscriptions/'.$subscription->confirm_token, 'link', null, true);
    $confirm_url = secure_asset('subscriptions/'.$subscription->confirm_token);
    $map_image_link = 'https://api.tiles.mapbox.com/v4/codeforafrica.ji193j10'.
      '/geojson('.urlencode($subscription->geojson).')'.
      '/auto/600x250.png?'.
      'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';

    // echo $subscription->bounds_geojson(); exit();

    $data = compact(
      'subscription', 'user', 'map_image_link',
      'confirm_link', 'confirm_url'
    );
    $view = View::make($view_name, $data);
    
    if (Input::get('inline', 0) == 1) {
      return Inliner::inline($view);
    }
    return $view;
  }


}
