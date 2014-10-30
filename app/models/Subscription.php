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
        $data = array(
          'subscription' => $subscription,
          'user' => $user,
          'confirm_token' => $subscription->confirm_token,
          'confirm_link' => link_to('subscription/confirm/'.$subscription->confirm_token, 'link', null, true),
          'confirm_url' => secure_asset('subscription/confirm/'.$subscription->confirm_token)
        );

        Mail::queue('emails.subscription.new', $data, function($message) use ($user)
        {
          $message->to($user->email)->subject('Confirm Subscription! | #GreenAlert');
        });

      });

    }

}
