<?php namespace AlertME;

use AlertME\Commands\AlertQueue;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model {

    /*
     * STATUS CODES
     * ------------
     * 0: Created (Default)
     * 1:
     * 2:
     * 3:
     */

    public static function boot()
    {
        parent::boot();

        // Setup event bindings...
        Alert::created(function ($alert) {
            \Queue::push( new AlertQueue($alert->id));
        });

    }

    function project()
    {
        return $this->belongsTo('AlertME\Project');
    }

    public function subcriptions()
    {
        return $this->belongsToMany('AlertME\Subscription', 'subscription_alert');
    }

}
