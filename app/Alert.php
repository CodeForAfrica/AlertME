<?php namespace Greenalert;

use Greenalert\Commands\AlertQueue;
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
        return $this->belongsTo('Greenalert\Project');
    }

    public function subcriptions()
    {
        return $this->belongsToMany('Greenalert\Subscription', 'subscription_alert');
    }

}
