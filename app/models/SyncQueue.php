<?php

class SyncQueue {

  public function fire($job, $data)
  {
    Log::info('['.$job->getJobId().':'.$job->attempts().'] Sync started.');

    ini_set ( 'memory_limit', '256M' );

    if ($job->attempts() > 3)
    {
      Log::info('['.$job->getJobId().':'.$job->attempts().'] Sync failed.');
      $job->delete();

    } else {

      $sync = Sync::find($data['sync_id']);
      $datasources = DataSource::where('config_status', 1)->get();

      foreach ($datasources as $datasource) {
        $datasource->sync($sync);
      }

      // Set Categories once sync is done
      $categories = Category::all();
      foreach ($categories as $category) {
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
