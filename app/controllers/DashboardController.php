<?php

class DashboardController extends BaseController {

  public function showHome()
  {
    return View::make('dashboard.home');
  }

  public function showSettings()
  {
    return View::make('dashboard.settings');
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
    $datasources = DataSource::all();

    $data = array(
      'datasources' => $datasources
    );

    return View::make('dashboard.categories', $data);
  }

}
