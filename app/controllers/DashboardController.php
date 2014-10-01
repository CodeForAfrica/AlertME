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

}
