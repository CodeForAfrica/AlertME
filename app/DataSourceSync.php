<?php namespace AlertME;

use Illuminate\Database\Eloquent\Model;

class DataSourceSync extends Model {

    protected $table = 'data_source_syncs';

    /**
     * SYNC STATUS
     * -------------
     * 0: Failed (Other)
     * 1: Successfully Synced
     * 2: Old Data Source Sync
     * 3: New Data Source Sync
     * 4: Failed on CSV Fetch
     * 5: Columns Do Not Match (Reconfigure)
     */

    public static function boot()
    {
        parent::boot();

        // Setup event bindings...
        DataSourceSync::created(function($ds_sync)
        {

        });
    }

    function sync()
    {
        return $this->belongsTo('AlertME\Sync');
    }

    function datasource()
    {
        return $this->belongsTo('AlertME\DataSource');
    }

    function projects()
    {
        return $this->hasMany('AlertME\Project');
    }

}
