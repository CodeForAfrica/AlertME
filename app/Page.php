<?php namespace AlertME;

use Illuminate\Database\Eloquent\Model;

class Page extends Model {

    protected $fillable = array('slug');

    public static function boot()
    {
        parent::boot();

        // Setup event bindings...
        Page::created(function ($alertuser) {

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
