<?php

class SyncQueue {

  public function fire($job, $data)
  {
    $sync = Sync::find($data['sync_id']);
    $ds_configs = DataSourceConfig::where('config_status', 1)->get();

    foreach ($ds_configs as $ds_config) {
      self::dataSourceSync($sync, $ds_config);
    }

    $sync->sync_status = 1;
    $sync->save();

    $job->delete();
  }

  function dataSourceSync($sync, $ds_config)
  {
    $ds_sync = new DataSourceSync;
    $ds_sync->sync_id = $sync->id;
    $ds_sync->data_source_id = $ds_config->data_source_id;
    $ds_sync->sync_status = 2;
    $ds_sync->save();




    $ds_sync->sync_status = 1;
    $ds_sync->save();
  }

}
