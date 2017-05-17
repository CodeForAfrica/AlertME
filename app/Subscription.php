<?php namespace Greenalert;

use Greenalert\Mail\Subscribed;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;

class Subscription extends Model {

    use SoftDeletes;

    protected $table = 'subscriptions';

    protected $hidden = array('confirm_token');

    protected $dates = ['deleted_at'];

    /**
     * STATUS CODE
     * -----------
     * 0: Created
     * 1: Confirmed
     * 2: Suspended
     * 3: Deleted
     */

    public static function boot()
    {
        parent::boot();

        // Setup event bindings...
        Subscription::created(function($subscription)
        {
            Mail::to($subscription->user->email)->send(new Subscribed($subscription));
        });

    }

    public function alerts()
    {
        return $this->belongsToMany('Greenalert\Alert', 'subscription_alert');
    }

    public function user()
    {
        return $this->belongsTo('Greenalert\User');
    }

    public function project()
    {
        return $this->belongsTo('Greenalert\Project');
    }


    // Accessors & Mutators
    public function getSwLatAttribute($value)
    {
        return floatval($value);
    }
    public function getSwLngAttribute($value)
    {
        return floatval($value);
    }
    public function getNeLatAttribute($value)
    {
        return floatval($value);
    }
    public function getNeLngAttribute($value)
    {
        return floatval($value);
    }

}
