<?php namespace Greenalert\Http\Controllers;

use Greenalert\Category;
use Greenalert\DataSource;
use Greenalert\GeoApi;
use Greenalert\Http\Controllers\Controller;
use Greenalert\Page;
use Greenalert\Subscription;
use Greenalert\Sync;

use Greenalert\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showHome(Request $request)
    {
        $data = compact('request');
        return view('dashboard.home', $data);
    }


    public function showDataSources(Request $request)
    {
        $datasources = DataSource::all();

        $data = compact(
            'datasources', 'request'
        );

        return view('dashboard.datasources', $data);
    }

    public function syncDataSources(Request $request)
    {
        $sync = new Sync;
        $sync->sync_status = 2;
        $sync->user_id = $request->user()->id;
        $sync->save();

        return redirect('dashboard/datasources')->with('success', 'Data source sync started successfully.');
    }

    public function showCategories(Request $request)
    {
        $categories = Category::all();

        $data = compact(
            'categories', 'request'
        );

        return view('dashboard.categories', $data);
    }

    public function showPages(Request $request)
    {
        $home = Page::find(1);
        $about = Page::find(2);

        $data = compact(
            'home', 'about', 'request'
        );

        return view('dashboard.pages', $data);
    }

    public function setPages(Request $request)
    {
        $input = json_decode(json_encode($request->all()), false);

        $home = Page::find(1);
        $home->data = $input->home;
        $home->save();

        $about = Page::find(2);
        $about->data = $input->about;
        $about->save();

        return redirect('dashboard/pages')->with('success', 'Successfully saved pages.');
    }


    public function showSubscriptions(Request $request)
    {
        if ($request->user()->role_id == 1) {
            $subscriptions = Subscription::withTrashed()->paginate(10);
        } else {
            $subscriptions = User::find($request->user()->id)->subscriptions()->withTrashed()->paginate(10);
        }

        $data = compact(
            'subscriptions', 'request'
        );

        return view('dashboard.subscriptions', $data);
    }


    public function showProfile(Request $request)
    {
        $user = $request->user();
        $data = compact(
            'user', 'request'
        );

        return view('dashboard.profile', $data);
    }

    public function setProfile(Request $request)
    {
        // TODO: Better validation

        $user = $request->user();
        $user->fullname = $request->input('fullname');
        $user->save();

        $email_old = $user->email;
        $email_new = $request->input('email');
        if ($email_new != $email_old) {
            $validator = Validator::make(
                array('email' => $email_new),
                array('email' => 'required|email|unique:users')
            );
            if ($validator->fails()) {
                return redirect('dashboard/profile')->with('error', $validator->messages());
            }
            $user->email = $email_new;
            $user->save();
        }

        if ($request->input('password') != '') {
            if ($request->inputt('password') != $request->input('password_confirmation')) {
                return redirect('dashboard/profile')->with('error', 'Passwords don\'t match.');
            }
            $user->password = Hash::make($request->input('password'));
            $user->save();
        }

        return redirect('dashboard/profile')->with('success', 'Successfully saved profile changes.');
    }

    public function showSettings(Request $request)
    {
        $geoapi = GeoApi::find(1);

        $data = compact(
            'geoapi', 'request'
        );

        return view('dashboard.settings', $data);
    }

    public function setSettings(Request $request)
    {
        $geoapi = GeoApi::find(1);
        $geoapi->key = $request->input('key');
        $geoapi->save();

        return redirect('dashboard/settings')->with('success', 'Successfully saved settings.');
    }

}
