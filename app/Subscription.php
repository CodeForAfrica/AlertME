<?php namespace Greenalert;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
            $user = $subscription->user;
            $project_id = $subscription->project_id;

            $confirm_url = url('subscriptions/'.$subscription->confirm_token);

            if ($subscription->project_id == 0) {
                $map_image_link = 'https://api.tiles.mapbox.com/v4/codeforafrica.ji193j10'.
                    '/geojson('.urlencode($subscription->geojson).')'.
                    '/auto/600x250.png?'.
                    'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
            } else {
                $map_image_link = 'http://api.tiles.mapbox.com/v4/codeforafrica.ji193j10/'.
                    $subscription->geojson.'/600x250.png256?'.
                    'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
                $project_title = $subscription->project->title;
            }

            $data = compact(
                'subscription', 'user', 'project_id', 'project_title',
                'map_image_link', 'confirm_url'
            );

            \Mail::queue('emails.subscription.new', $data, function($message) use ($user)
            {
                $message->to($user->email)->subject('#GreenAlert | Confirm Subscription!');
            });

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
