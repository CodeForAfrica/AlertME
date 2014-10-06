<?php

class Category extends Eloquent {

    protected $table = 'categories';

    public static function boot()
    {
      parent::boot();

      // Setup event bindings...
      Category::creating(function($category)
      {

      });

      Category::created(function($category)
      {
        // Queue::push('CategoryQueue', array('category' => $category));
      });
    }

    function projects()
    {
      return $this->belongsToMany('Project');
    }

}
