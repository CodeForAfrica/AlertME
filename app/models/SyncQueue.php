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
        $datasource->syncData($sync);
      }


      // TODO: Check if all data sources synced successfully


      $sync->sync_status = 1;
      $sync->save();

      Log::info('['.$job->getJobId().':'.$job->attempts().'] Sync completed.');

      $job->delete();

    }

  }

}
