<?php namespace Greenalert\Http\Controllers;

use Greenalert\Category;
use Greenalert\DataSource;
use Greenalert\GeoApi;
use Greenalert\Http\Controllers\Controller;
use Greenalert\Page;
use Greenalert\Subscription;
use Greenalert\Sync;

use Greenalert\User;
use Illuminate\Http\Request;

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

    public function showHome()
    {
        return view('dashboard.home');
    }


    public function showDataSources()
    {
        $datasources = DataSource::all();

        $data = array(
            'datasources' => $datasources
        );

        return view('dashboard.datasources', $data);
    }

    public function syncDataSources()
    {
        $sync = new Sync;
        $sync->sync_status = 2;
        $sync->user_id = \Auth::id();
        $sync->save();

        return redirect('dashboard/datasources')->with('success', 'Data source sync started successfully.');
    }

    public function showCategories()
    {
        $categories = Category::all();

        $data = array(
            'categories' => $categories
        );

        return view('dashboard.categories', $data);
    }

    public function showPages()
    {
        $home = Page::find(1);
        $about = Page::find(2);

        $data = compact(
            'home', 'about'
        );

        return view('dashboard.pages', $data);
    }

    public function setPages()
    {
        $input = json_decode(json_encode(\Input::all()), false);

        $home = Page::find(1);
        $home->data = $input->home;
        $home->save();

        $about = Page::find(2);
        $about->data = $input->about;
        $about->save();

        return redirect('dashboard/pages')->with('success', 'Successfully saved pages.');
    }


    public function showSubscriptions()
    {
        if (\Auth::user()->role_id == 1) {
            $subscriptions = Subscription::withTrashed()->paginate(10);
        } else {
            $subscriptions = User::find(\Auth::id())->subscriptions()->withTrashed()->paginate(10);
        }

        $data = compact(
            'subscriptions'
        );

        return view('dashboard.subscriptions', $data);
    }


    public function showProfile()
    {
        $user = \Auth::user();
        $data = array(
            'user' => $user
        );

        return view('dashboard.profile', $data);
    }

    public function setProfile()
    {
        // TODO: Better validation

        $user = \Auth::user();
        $user->fullname = \Input::get('fullname');
        $user->save();

        $email_old = $user->email;
        $email_new = \Input::get('email');
        if ($email_new != $email_old) {
            $validator = \Validator::make(
                array('email' => $email_new),
                array('email' => 'required|email|unique:users')
            );
            if ($validator->fails()) {
                return redirect('dashboard/profile')->with('error', $validator->messages());
            }
            $user->email = $email_new;
            $user->save();
        }

        if (\Input::get('password') != '') {
            if (\Input::get('password') != \Input::get('password_confirmation')) {
                return redirect('dashboard/profile')->with('error', 'Passwords don\'t match.');
            }
            $user->password = \Hash::make(\Input::get('password'));
            $user->save();
        }

        return redirect('dashboard/profile')->with('success', 'Successfully saved profile changes.');
    }

    public function showSettings()
    {
        $geoapi = GeoApi::find(1);
        $data = array(
            'geoapi' => $geoapi
        );

        return view('dashboard.settings', $data);
    }

    public function setSettings()
    {
        $geoapi = GeoApi::find(1);
        $geoapi->key = \Input::get('key');
        $geoapi->save();

        return redirect('dashboard/settings')->with('success', 'Successfully saved settings.');
    }

}
