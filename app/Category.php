<?php namespace Greenalert;

use Greenalert\Commands\CategoryQueue;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    protected $table = 'categories';

    public static function boot()
    {
        parent::boot();

        // Setup event bindings...
        Category::creating(function ($category) {

        });

        Category::saving(function ($category) {

        });

        Category::created(function ($category) {
            \Queue::push(new CategoryQueue($category->id));
        });

        Category::updated(function ($category) {
            \Queue::push(new CategoryQueue($category->id));
        });

        Category::deleting(function ($category) {
            // TODO: Check whether there is a queue job with this - Locking?
        });

        Category::deleted(function ($category) {
            \DB::table('project_category')
                ->where('category_id', $category->id)
                ->delete();
        });
    }


    // Relations

    public function projects()
    {
        return $this->belongsToMany('Greenalert\Project')->withTimestamps();
    }


    // Other Functions

    static function geocoded()
    {
        $sel_cols_projects = array('projects.id', 'projects.data_id',
            'projects.title', 'projects.description', 'projects.geo_type',
            'projects.geo_address', 'projects.geo_lat', 'projects.geo_lng',
            'projects.status');
        $sel_cols_geocodes = array('geocodes.lat', 'geocodes.lng',
            'geocodes.status as geo_status');
        $projects_lat_lng = \DB::table('projects')
            ->where('geo_type', 'lat_lng')
            ->select($sel_cols_projects)->get()->all();
        $projects_address = \DB::table('projects')
            ->join('geocodes', 'projects.geo_address', '=', 'geocodes.address')
            ->where('projects.geo_type', '=', 'address')
            ->select(array_merge($sel_cols_projects, $sel_cols_geocodes))->get()->all();
        $projects = array_merge($projects_lat_lng, $projects_address);

        $projects_id = array();
        foreach ($projects as $project) {
            array_push($projects_id, $project->id);
        }
        $projects_categories = \DB::table('project_category')
            ->whereIn('project_id', $projects_id)
            ->select('category_id')
            ->distinct()
            ->get();
        $category_ids = array();
        foreach ($projects_categories as $projects_category) {
            array_push($category_ids, $projects_category->category_id);
        }

        if (count($category_ids) == 0) {
            return null;
        }

        $categories = Category::whereIn('id', $category_ids)->get();

        return $categories;
    }

}
