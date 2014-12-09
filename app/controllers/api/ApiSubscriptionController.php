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
    $subscriptions = Subscription::paginate(10);;
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
    // VALIDATORS

    // Validate type
    $rules = array(
      'type' => array('required', 'in:project,map')
    );
    $messages = array(
      'type.required' => 'NO_TYPE'
    );
    $validator = Validator::make(Input::all(), $rules, $messages);
    if ($validator->fails()) {
      return Response::json(
        array(
          'error' => true,
          'validator' => $validator->messages()
        ),
        500
      );
    }

    // Validate email + duplicate
    if (Input::get('type') == 'project') {
      $confirm_token = md5(Input::get('project_id').Input::get('email'));
    } elseif (Input::get('type') == 'map') {
      $confirm_token = md5(Input::get('bounds').Input::get('email'));
    }
    $data = Input::all();
    $data = array_add($data , 'confirm_token', $confirm_token);
    $rules = array(
      'email' => array('required','email'),
      'confirm_token' => 'unique:subscriptions'
    );
    $messages = array(
      'email.required' => 'NO_EMAIL',
      'email.email' => 'NOT_EMAIL',
      'confirm_token.unique' => 'DUPLICATE'
    );
    $validator = Validator::make($data, $rules, $messages);
    if ($validator->fails()) {
      return Response::json(
        array(
          'error' => true,
          'validator' => $validator->messages()
        ),
        500
      );
    }

    // Validate bounds
    if (Input::get('type') == 'map') {
      $bounds = explode(",", Input::get('bounds'));
      if ( count($bounds) != 4 || !Input::has('bounds')){
        return Response::json(array(
          'error' => true,
          'validator' => 'BOUNDS_ERROR'),
          500
        );
      }
    }


    // SUBSCRIBE

    // Create User or First
    User::firstOrCreate(array('email' => Input::get('email')));
    $user = User::where('email', Input::get('email'))->first();

    $subscription = new Subscription;
    $subscription->user_id = $user->id;
    $subscription->confirm_token = $confirm_token;
    $subscription->geojson = Input::get('geojson');

    if (Input::get('type') == 'project') {

      $subscription->project_id = Input::get('project_id');
      
    } elseif (Input::get('type') == 'map') {

      $subscription->sw_lat = $bounds[0];
      $subscription->sw_lng = $bounds[1];
      $subscription->ne_lat = $bounds[2];
      $subscription->ne_lng = $bounds[3];
      
      $subscription->bounds = Input::get('bounds');
      $subscription->center = Input::get('center');
      $subscription->zoom = Input::get('zoom');
      
    }

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

    $map_image_link = 'https://api.tiles.mapbox.com/v4/codeforafrica.ji193j10'.
      '/geojson('.urlencode($subscription->geojson).')'.
      '/auto/600x250.png?'.
      'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';

    $map_link = secure_asset('map/#!/bounds='.$subscription->bounds);

    $user_email = substr(explode("@", $user->email)[0], 0, 1);
    for ($i=0; $i < strlen(substr(explode("@", $user->email)[0], 1)); $i++) { 
      $user_email .= 'x';
    }
    $user_email .= '@'.explode("@", $user->email)[1];
    
    $data = compact(
      'msg_confirm', 'msg_details',
      'subscription', 'user', 'user_email',
      'map_image_link', 'map_link'
    );
    return View::make('subscriptions.confirm', $data);
  }



  public function email()
  {
    //
    $subscription = Subscription::first();
    $user = User::find($subscription->user_id);

    // Get first project
    $project = Project::first();
    $project_geo = $project->geo();
    if (strlen($project->title) > 80) {
      $project->title = substr($project->title, 0, 80).'...';
    }
    if (strlen($project->description) > 200) {
      $project->description = substr($project->description, 0, 200).'...';
    }

    // Check email type
    if (preg_match('/alert*/', Input::get('type'))) {
      $view_name = 'emails.alerts.default';
      $map_image_link = 'https://api.tiles.mapbox.com/v4/codeforafrica.ji193j10'.
        '/pin-l-star+27AE60('.$project_geo->lng.','.$project_geo->lat.')'.
        '/'.$project_geo->lng.','.$project_geo->lat.',11'.
        '/600x250.png?'.
        'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';

      if (Input::get('type') == 'alert_status') {
        $view_name = 'emails.alerts.status';
      }
    } else {
      $view_name = 'emails.subscription.new';
      $map_image_link = 'https://api.tiles.mapbox.com/v4/codeforafrica.ji193j10'.
        '/geojson('.urlencode($subscription->geojson).')'.
        '/auto/600x250.png?'.
        'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
    }

    // New Subscription
    $confirm_link = link_to('subscriptions/'.$subscription->confirm_token, 'link', null, true);
    $confirm_url = secure_asset('subscriptions/'.$subscription->confirm_token);

    $data = compact(
      'subscription', 'user', 'map_image_link',
      'confirm_link', 'confirm_url',
      'project'
    );
    $view = View::make($view_name, $data);
    
    if (Input::get('inline', 0) == 1) {
      return Inliner::inline($view);
    }
    return $view;
  }


}
