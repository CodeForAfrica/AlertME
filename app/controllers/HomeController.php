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

		$categories = Category::geocoded();

		$data = array(
			'projects' => $projects,
			'categories' => $categories
		);
		return View::make('home.map', $data);
	}

	public function getSearch()
	{
		$q = Input::get('q');

		$projects_sql = Project::whereRaw(
			"MATCH(title, description, geo_address, status) AGAINST (? IN BOOLEAN MODE)", 
			array($q)
		);
		$projects_count = $projects_sql->count();
		$projects = $projects_sql->take(10)->get();

		$data = array(
			'projects' => $projects,
			'projects_count' => $projects_count
		);
		return View::make('home.search', $data);
	}

	public function showProject($id)
	{
		$project = Project::findOrFail($id);

		$map_image_link = 'http://api.tiles.mapbox.com/v4/codeforafrica.ji193j10/'.
			'pin-l-circle-stroked+1abc9c('.$project->geo()->lng.','.$project->geo()->lat.')/'.
			$project->geo()->lng.','.$project->geo()->lat.'),13'.
			'/520x293.png256?'.
			'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';

		$cols = DataSource::find($project->data_source_id)->datasourceconfig->data_source_columns;
		$cols = json_decode($cols);
		$project_data = $project->datasourcedata_single();

		$data = compact(
			'project', 'map_image_link',
			'cols', 'project_data'
		);
		return View::make('home.project', $data);
	}

}
