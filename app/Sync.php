<?php namespace AlertME;

use AlertME\Commands\SyncQueue;
use Illuminate\Database\Eloquent\Model;

class Sync extends Model {

    /**
     * SYNC STATUS
     * -------------
     * 0: All data sources failed to sync
     * 1: All data sources successfully synced
     * 2: Sync Starting
     * 3: Sync Started
     * 4: Partial sync failure
     */

    public static function boot()
    {
        parent::boot();

        // Setup event bindings...
        Sync::creating(function($sync)
        {
            if ( ! $sync->user_id ) return false;
        });

        Sync::created(function($sync)
        {
            \Queue::push(new SyncQueue($sync->id));
        });
    }

    function datasourcesyncs()
    {
        return $this->hasMany('AlertME\DataSourceSync');
    }

    function user()
    {
        return $this->belongsTo('AlertME\User');
    }

}
