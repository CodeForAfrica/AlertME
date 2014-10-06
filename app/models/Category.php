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

      Category::saving(function($category)
      {

      });

      Category::created(function($category)
      {
        $cat_old = Category::find($category->id);
        Queue::push('CategoryQueue', array(
          'cat_id' => $category->id,
          'cat_new' => $category,
          'cat_old' => $cat_old,
          'new' => true
        ));
      });

      Category::updating(function($category)
      {
        $cat_old = Category::find($category->id);
        Queue::push('CategoryQueue', array(
          'cat_id' => $category->id,
          'cat_new' => $category,
          'cat_old' => $cat_old,
          'new' => false
        ));
      });
      Category::deleted(function($category)
      {
        self::keywordUnAssign($category);
      });
    }

    function projects()
    {
      return $this->belongsToMany('Project');
    }

    static function keywordUnAssign ( $category )
    {
      DB::table('project_category')->where('category_id', $category->id)->delete();
    }

}
