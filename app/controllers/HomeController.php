<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showHome');
	|
	*/

	public function showHome()
	{
		$projects = DB::table('projects')->take(10)->get();
		$projects_count = DB::table('projects')->count();

		$categories = $projects;

		$data = array(
			'projects' => $projects,
			'projects_count' => $projects_count
		);
		return View::make('home.index', $data);
	}

	public function showAbout()
	{
		$about = Page::find(1);
		$data = array(
			'about' => $about
		);
		return View::make('home.about', $data);
	}

	public function showMap()
	{
		$projects = DB::table('projects')->take(10)->get();

		$categories = $projects;

		$data = array(
			'projects' => $projects
		);
		return View::make('home.map', $data);
	}

}
