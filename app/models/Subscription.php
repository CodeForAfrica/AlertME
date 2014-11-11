<?php

class Subscription extends Eloquent {

    protected $table = 'subscriptions';

    protected $hidden = array('confirm_token');

    public static function boot()
    {
      parent::boot();

      // Setup event bindings...
      Subscription::created(function($subscription)
      {
        $user = DB::table('users')->where('id', $subscription->user_id)->first();
        $confirm_link = link_to('subscriptions/'.$subscription->confirm_token, 'link', null, true);
        $confirm_url = secure_asset('subscriptions/'.$subscription->confirm_token);
        $map_image_link = 'https://api.tiles.mapbox.com/v4/codeforafrica.ji193j10'.
          '/geojson('.urlencode($subscription->geojson).')'.
          '/auto/600x250.png?'.
          'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
        $data = compact(
          'subscription', 'user',
          'map_image_link', 'confirm_link', 'confirm_url'
        );

        Mail::queue('emails.subscription.new', $data, function($message) use ($user)
        {
          $message->to($user->email)->subject('#GreenAlert | Confirm Subscription!');
        });

      });

    }

    public function alerts()
    {
        return $this->belongsToMany('Alert', 'subscription_alert');
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
