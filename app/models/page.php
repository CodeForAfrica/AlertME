<?php

class Page extends Eloquent {

    protected $table = 'pages';
    protected $fillable = array('slug');

    public static function boot()
    {
      parent::boot();

      // Setup event bindings...
      Page::created(function($alertuser)
      {

      });

    }

    public function getDataAttribute($value)
    {
        return json_decode($value);
    }
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }

}
