<?php

class DashboardController extends BaseController {

  public function showHome()
  {
    return View::make('dashboard.home');
  }


  public function showDataSources()
  {
    $datasources = DataSource::all();

    $data = array(
      'datasources' => $datasources
    );

    return View::make('dashboard.datasources', $data);
  }

  public function syncDataSources()
  {
    $sync = new Sync;
    $sync->sync_status = 2;
    $sync->user_id = Auth::id();
    $sync->save();

    return Redirect::to('dashboard/datasources')->with('success', 'Data source sync started successfully.');
  }


  public function showCategories()
  {
    $categories = Category::all();

    $data = array(
      'categories' => $categories
    );

    return View::make('dashboard.categories', $data);
  }


  public function showSettings()
  {
    $geoapi = GeoApi::find(1);
    $data = array(
      'geoapi' => $geoapi
    );
    return View::make('dashboard.settings', $data);
  }

  public function setSettings()
  {
    $geoapi = GeoApi::find(1);
    $geoapi->key = Input::get('key');
    $geoapi->save();
    return Redirect::to('dashboard/settings')->with('success', 'Successfully saved settings');
  }

}
