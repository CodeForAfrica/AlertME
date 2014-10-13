<?php

class AlertRegistration extends Eloquent {

    protected $table = 'alert_registrations';

    public static function boot()
    {
      parent::boot();

      // Setup event bindings...
      AlertRegistration::created(function($alert)
      {

      });

    }

    public function users()
    {
      return $this->belongsTo('AlertUser');
    }
}
