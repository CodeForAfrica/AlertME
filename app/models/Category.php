<?php

class Category extends Eloquent {

    protected $table = 'categories';

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
        Queue::push('SyncQueue', array('sync_id' => $sync->id));
      });
    }

    function projects()
    {
      return $this->belongsToMany('Project');
    }

}
