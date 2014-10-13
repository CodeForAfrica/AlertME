<?php

class AlertUser extends Eloquent {

    protected $table = 'alert_users';

    public static function boot()
    {
      parent::boot();

      // Setup event bindings...
      AlertUser::created(function($alertuser)
      {

      });

    }

    public function alerts()
    {
      return $this->hasMany('AlertRegistrations');
    }
}
