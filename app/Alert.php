<?php namespace Greenalert;

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
            Queue::push('AlertQueue', array('id' => $alert->id));
        });

    }

    function project()
    {
        return $this->belongsTo('GreenAlert\Project');
    }

    public function subcriptions()
    {
        return $this->belongsToMany('GreenAlert\Subscription', 'subscription_alert');
    }

}
