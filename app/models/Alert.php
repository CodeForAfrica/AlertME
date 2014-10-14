<?php

class Alert extends Eloquent {

    protected $table = 'alerts';
    protected $fillable = array('project_id');

    public static function boot()
    {
      parent::boot();

      // Setup event bindings...
      Alert::created(function($alert)
      {
        Queue::push('AlertQueue', array('alert_id' => $alert->id));
      });

    }

    function project() {
      return $this->belongsTo('Project');
    }

}
