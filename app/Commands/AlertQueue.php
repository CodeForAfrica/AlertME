<?php namespace Greenalert\Commands;

use Greenalert\Alert;

use Greenalert\Project;
use Greenalert\Subscription;
use Greenalert\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;

class AlertQueue extends Command implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels;

    public $alert_id;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($alert_id)
    {
        $this->alert_id = $alert_id;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $alert = Alert::find($this->alert_id);
        if ($alert->status != 0) {
            return;
        } else {
            $alert->status = 1;
            $alert->save();
        }

        $project = Project::find($alert->project_id);
        $project_lat = $project->geo->lat;
        $project_lng = $project->geo->lng;

        if ($project_lat != 450.0 && $project_lng != 450.0) {
            $subscriptions = Subscription::where('sw_lat', '<', $project_lat)
                ->where('sw_lng', '<', $project_lng)
                ->where('ne_lat', '>', $project_lat)
                ->where('ne_lng', '>', $project_lng)
                ->select('user_id')
                ->distinct()
                ->get();
            foreach ($subscriptions as $subscription) {
                $user = User::find($subscription->user_id);
                $data = compact('user', 'project');
                \Mail::queue('emails.alerts.status', $data, function ($message) use ($user) {
                    $message->to($user->email, '')->subject('[#GreenAlert] You\'ve got an update');
                });
            }
        }
    }

}
