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
  | Route::get('/', 'HomeController@showHome');
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
    $page = Input::get('page', 1);
    $offset = $page * 10;
    if ($page == 1) {
      $offset = 0;
    }

    $projects_sql = Project::whereRaw(
      "MATCH(title, description, geo_address, status) AGAINST (? IN BOOLEAN MODE)", 
      array($q)
    );
    $projects_count = $projects_sql->count();
    $projects = $projects_sql->skip($offset)->take(10)->get();


    // Limit length
    for ($i=0; $i < count($projects); $i++) { 
      if (strlen($projects[$i]->title) > 80) {
        $projects[$i]->title = substr($projects[$i]->title, 0, 80).'...';
      }
      if (strlen($projects[$i]->description) > 200) {
        $projects[$i]->description = substr($projects[$i]->description, 0, 200).'...';
      }
    }
    

    // Pagination
    $pagination_html = '';
    $pagination_html_prev = '<li class="previous">'.
        '<a href="/search?q='.$q.'&page='.($page-1).'" class="fui-arrow-left"></a>'.
      '</li>';
    $pagination_html_nav = '';
    $pagination_html_more = '';
    $pagination_html_next = '<li class="next disabled">'.
        '<a href="javascript:void(0);" class="fui-arrow-right"></a>'.
      '</li>';
    $pagination_set = floor($offset/110)*10;
    $pagination_max = floor($projects_count/11)+1;
    if ( $page == 1 ) {
      // On first page
      $pagination_html_prev = '<li class="previous disabled">'.
          '<a href="javascript:void(0);" class="fui-arrow-left"></a>'.
        '</li>';
    }
    if ( $projects_count/10 > 1 ) {
      // More than 10 projects
      $pagination_html_next = '<li class="next">'.
          '<a href="/search?q='.$q.'&page='.($page+1).'" class="fui-arrow-right"></a>'.
        '</li>';
      for ( $i = $pagination_set ; $i < $pagination_max ; $i++ ) { 
        if ($i == $pagination_set + 10) {
          break;
        }
        if ( $page == $i + 1 ) {
          $pagination_html_nav .= '<li class="active">'.
            '<a href="javascript:void(0);">'.($i+1).'</a></li>';
        } else {
          $pagination_html_nav .= '<li>'.
            '<a href="/search?q='.$q.'&page='.($i+1).'">'.($i+1).'</a></li>';
        }
      }
      if ($projects_count/10 > 10) {
        $pagination_html_more = '<li class="pagination-dropdown dropup">'.
            '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.
              '<i class="fui-triangle-up"></i>'.
            '</a>'.
            '<ul class="dropdown-menu">';
        for ( $i=0; $i < ($pagination_max/10); $i++ ) { 
          if ($i == ($pagination_set/10)) {
            continue;
          }
          $pagination_html_more .= '<li>'.
              '<a href="/search?q='.$q.'&page='.(($i*10)+1).'">'.
                (($i*10)+1).'-'.(($i+1)*10).
              '</a>'.
            '</li>';
        }
        $pagination_html_more .= '</ul></li>';
      }
    } else {
      // Less than 10 projects
      $pagination_html_nav = '<li class="disabled">'.
        '<a href="javascript:void(0);">1</a></li>';
    }
    $pagination_html = $pagination_html_prev.
      $pagination_html_nav.$pagination_html_more.
      $pagination_html_next;



    $data = compact(
      'projects', 'projects_count',
      'pagination_html'
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
