<?php

namespace AlertME\Mail;

use AlertME\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Subscribed extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;
    public $project_id;
    public $project_title;
    public $confirm_url;
    public $map_image_link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
        $this->project_id = $subscription->project_id;
        $this->project_title = '';

        $this->confirm_url = url('subscriptions/'.$subscription->confirm_token);

        if ($subscription->project_id == 0) {
            $this->map_image_link = 'https://api.tiles.mapbox.com/v4/codeforafrica.ji193j10'.
                '/geojson('.urlencode($subscription->geojson).')'.
                '/auto/600x250.png?'.
                'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
        } else {
            $this->map_image_link = 'http://api.tiles.mapbox.com/v4/codeforafrica.ji193j10/'.
                $subscription->geojson.'/600x250.png256?'.
                'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
            $this->project_title = $subscription->project->title;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.subscription.new');
    }
}
