<?php

namespace Greenalert\Http\Controllers;

use Greenalert\Category;
use Greenalert\Page;
use Greenalert\Project;
use Illuminate\Http\Request;

class HomeController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | Something commented
    |
    */

    public function showHome(Request $request)
    {
        $home = Page::find(1);

        $projects = \DB::table('projects')->take(10)->get();
        $projects_count = \DB::table('projects')->count();

        $data = compact(
            'home', 'projects', 'projects_count', 'request'
        );

        return view('home.index', $data);
    }


    public function showAbout(Request $request)
    {
        $about = Page::find(2);
        $data = compact(
            'about', 'request'
        );

        return view('home.about', $data);
    }


    public function showMap(Request $request)
    {
        $projects = \DB::table('projects')->take(10)->get();
        $projects_all = Project::select('id', 'geo_lat', 'geo_lng')->hasGeo()->get();

        $categories = Category::geocoded();
        if ($categories) {
            foreach ($categories as $key => $category) {
                $pivot = \DB::table('project_category')
                    ->where('category_id', $category->id)
                    ->pluck('project_id');
                $categories[ $key ] = array_add($categories[ $key ], 'projects_pivot', $pivot);
            }
        }

        $data = compact(
            'projects', 'projects_all', 'categories', 'request'
        );

        return view('home.map', $data);
    }


    public function getSearch(Request $request)
    {
        $q = $request->input('q');

        $projects = Project::search($q)->paginate(10);

        // Limit length
        for ($i = 0; $i < count($projects); $i++) {
            if (strlen($projects[ $i ]->title) > 80) {
                $projects[ $i ]->title = substr($projects[ $i ]->title, 0, 80) . '...';
            }
            if (strlen($projects[ $i ]->description) > 200) {
                $projects[ $i ]->description = substr($projects[ $i ]->description, 0, 200) . '...';
            }
        }

        $data = compact(
            'projects', 'request'
        );

        return view('home.search', $data);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function showProject(Request $request, $id)
    {
        $project = Project::find($id);

        if ($id == 'random') {
            $projects = Project::all();
            $project = $projects[ mt_rand(0, count($projects) - 1) ];
        }

        if (!$project) {
            return redirect('search')->with('error', 'Oops! It seems we can\'t find the page you are looking for. Try search instead.');
        }

        $geojson = 'pin-l-circle-stroked+1abc9c(' . $project->geo()->lng . ',' . $project->geo()->lat . ')/' .
            $project->geo()->lng . ',' . $project->geo()->lat . '),13';

        $map_image_link = 'https://api.tiles.mapbox.com/v4/codeforafrica.ji193j10/' .
            $geojson . '/520x293.png256?' .
            'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';

        $data = compact(
            'project', 'map_image_link', 'geojson', 'request'
        );

        return view('home.project', $data);
    }

}
