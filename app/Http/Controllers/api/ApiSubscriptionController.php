<?php namespace Greenalert\Http\Controllers\api;

use Greenalert\Http\Controllers\Controller;

use Greenalert\Project;
use Greenalert\Subscription;
use Greenalert\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

class ApiSubscriptionController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $subscriptions = Subscription::paginate(10);

        return response()->json(array(
            'error'         => false,
            'subscriptions' => $subscriptions->toArray(),
            'status'        => 'OK'),
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
    public function store(Request $request)
    {
        // VALIDATORS

        // Validate type
        $rules = array(
            'type' => array('required', 'in:project,map')
        );
        $messages = array(
            'type.required' => 'NO_TYPE'
        );
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(
                array(
                    'error'     => true,
                    'validator' => $validator->messages()
                ),
                500
            );
        }

        // TODO: Validate project_id + email

        // Validate email + duplicate
        if ($request->input('type') == 'project') {
            $confirm_token = md5($request->input('project_id') . $request->input('email'));
        } else {
            $confirm_token = md5($request->input('bounds') . $request->input('email'));
        }
        $data = $request->all();
        $data = array_add($data, 'confirm_token', $confirm_token);
        $rules = array(
            'email'         => array('required', 'email'),
            'confirm_token' => 'unique:subscriptions'
        );
        $messages = array(
            'email.required'       => 'NO_EMAIL',
            'email.email'          => 'NOT_EMAIL',
            'confirm_token.unique' => 'DUPLICATE'
        );
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(
                array(
                    'error'     => true,
                    'validator' => $validator->messages()
                ),
                500
            );
        }

        // Validate bounds
        if ($request->input('type') == 'map') {
            $bounds = explode(",", $request->input('bounds'));
            if (count($bounds) != 4 || $request->has('bounds')) {
                return response()->json(array(
                    'error'     => true,
                    'validator' => 'BOUNDS_ERROR'),
                    500
                );
            }
        }


        // SUBSCRIBE

        User::firstOrCreate(array('email' => $request->input('email')));
        $user = User::where('email', $request->input('email'))->first();

        $subscription = new Subscription;
        $subscription->user_id = $user->id;
        $subscription->confirm_token = $confirm_token;
        $subscription->geojson = $request->input('geojson');

        if ($request->input('type') == 'project') {

            $subscription->project_id = \Input::get('project_id');

        } elseif (\Input::get('type') == 'map') {

            $subscription->sw_lat = $bounds[0];
            $subscription->sw_lng = $bounds[1];
            $subscription->ne_lat = $bounds[2];
            $subscription->ne_lng = $bounds[3];

            $subscription->bounds = $request->input('bounds');
            $subscription->center = $request->input('center');
            $subscription->zoom = $request->input('zoom');

        }

        $subscription->save();

        $user->increment('subscriptions');

        return response()->json(array(
            'error'        => false,
            'subscription' => $subscription->toArray(),
            'status'       => 'OK'
        ),
            200
        );
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
    public function destroy(Request $request, $id)
    {
        $subscription = Subscription::withTrashed()->find($id);

        if ($subscription->confirm_token != $request->input('confirm_token')) {
            return response()->json(array(
                'error'   => true,
                'message' => 'Sorry, we were not able to unsubscribe you.',
                'status'  => 'UNSUBSCRIBE_FAILED'
            ),
                405
            );
        }

        if ($request->input('restore') == 1) {
            $subscription->status = 1;
            $subscription->save();
            $subscription->restore();
        } else {
            $subscription->status = 2;
            $subscription->save();
            $subscription->delete();
        }

        return response()->json(array(
            'error'        => false,
            'subscription' => $subscription->toArray(),
            'status'       => 'DELETED'
        ),
            200
        );
    }


    /**
     * Confirm Subscription
     *
     * @param  string $confirm_token
     * @return View
     */
    public function confirm(Request $request, $confirm_token)
    {
        //
        $subscription = Subscription::withTrashed()->where('confirm_token', $confirm_token)->firstOrFail();
        if ($subscription->status == 0) {
            $subscription->status = 1;
            $subscription->save();
            $msg_confirm = 'Confirmed';
        }

        $user = User::find($subscription->user_id);
        if ($request->input('fullname')) {
            $user->fullname = $request->input('fullname');
            $user->save();
            $msg_details = 'Updated';
        }

        if ($subscription->project_id == 0) {
            $map_image_link = 'https://api.tiles.mapbox.com/v4/codeforafrica.ji193j10' .
                '/geojson(' . urlencode($subscription->geojson) . ')' .
                '/auto/600x250.png?' .
                'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
            $map_link = secure_asset('map/#!/bounds=' . $subscription->bounds);
        } else {
            $map_image_link = 'https://api.tiles.mapbox.com/v4/codeforafrica.ji193j10/' .
                $subscription->geojson . '/600x250.png256?' .
                'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
            $map_link = secure_asset('map/#!/center=' .
                $subscription->project->geo_lat . ',' . $subscription->project->geo_lng .
                '&zoom=11');
        }


        $user_email = substr(explode("@", $user->email)[0], 0, 1);
        for ($i = 0; $i < strlen(substr(explode("@", $user->email)[0], 1)); $i++) {
            $user_email .= 'x';
        }
        $user_email .= '@' . explode("@", $user->email)[1];

        $data = compact(
            'msg_confirm', 'msg_details',
            'subscription', 'user', 'user_email',
            'map_image_link', 'map_link'
        );

        return view('subscriptions.confirm', $data);
    }


    public function email(Request $request)
    {
        // Get fisrt subscription
        $subscription = Subscription::first();
        $user = User::find($subscription->user_id);

        // Get first project
        $project = Project::first();
        $project_geo = $project->geo();
        if (strlen($project->title) > 80) {
            $project->title = substr($project->title, 0, 80) . '...';
        }
        if (strlen($project->description) > 200) {
            $project->description = substr($project->description, 0, 200) . '...';
        }

        // Check email type
        if (preg_match('/alert*/', $request->input('type'))) {
            $view_name = 'emails.alerts.default';
            $map_image_link = 'https://api.tiles.mapbox.com/v4/codeforafrica.ji193j10' .
                '/pin-l-star+27AE60(' . $project_geo->lng . ',' . $project_geo->lat . ')' .
                '/' . $project_geo->lng . ',' . $project_geo->lat . ',11' .
                '/600x250.png?' .
                'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';

            if (\Input::get('type') == 'alert_status') {
                $view_name = 'emails.alerts.status';
            }
            $project_title = $project->title;
            $project_id = $project->id;
        } else {
            $view_name = 'emails.subscription.new';
            $map_image_link = 'https://api.tiles.mapbox.com/v4/codeforafrica.ji193j10' .
                '/geojson(' . urlencode($subscription->geojson) . ')' .
                '/auto/600x250.png?' .
                'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
            $project_id = $subscription->project_id;
        }

        // New Subscription
        $confirm_url = secure_asset('subscriptions/' . $subscription->confirm_token);

        $data = compact(
            'subscription', 'user', 'map_image_link',
            'confirm_url', 'project_title', 'project_id'
        );
        $view = view($view_name, $data);

        if ($request->input('inline', 0) == 1) {
            // TODO: Make inline view
            // return Inliner::inline($view);
        }

        return $view;
    }

}
