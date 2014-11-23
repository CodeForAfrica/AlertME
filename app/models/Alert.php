<?php

class Alert extends Eloquent {

    protected $table = 'alerts';

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
      Alert::created(function($alert)
      {
        Queue::push('AlertQueue', array('id' => $alert->id));
      });

    }

    function project() {
      return $this->belongsTo('Project');
    }

    public function subcriptions()
    {
        return $this->belongsToMany('Subscription', 'subscription_alert');
    }

}
