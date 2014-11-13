<?php

class SyncQueue {

  public function fire($job, $data)
  {
    Log::info('['.$job->getJobId().':'.$job->attempts().'] Sync started.');

    if ($job->attempts() > 3)
    {
      Log::info('['.$job->getJobId().':'.$job->attempts().'] Sync failed.');
      $job->delete();

    } else {

      $sync = Sync::find($data['sync_id']);
      $ds_configs = DataSourceConfig::where('config_status', 1)->get();

      foreach ($ds_configs as $ds_config) {
        $datasource = DataSource::find($ds_config->datasource_id);
        $datasource->sync($sync);
      }

      // Set Categories once sync is done
      $categories = Category::all();
      foreach ($ategories as $category) {
        Queue::push('CategoryQueue', array(
          'cat_id' => $category->id,
          'cat_new' => $category,
          'cat_old' => $category,
          'new' => false
        ));
      }


      // TODO: Check if all data sources synced successfully


      $sync->sync_status = 1;
      $sync->save();

      Log::info('['.$job->getJobId().':'.$job->attempts().'] Sync completed.');

      $job->delete();

    }

  }

}
