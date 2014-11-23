<?php

class AlertQueue {

  public function fire($job, $data)
  {
    // Log::info('['.$job->getJobId().':'.$job->attempts().'] Alert happening.');

    $alert = Alert::find($data['id']);
    if($alert->status != 0) {
      $job->delete();
      return;
    } else {
      $alert->status = 1;
      $alert->save();
    }

    $project = Project::find($alert->project_id);
    $project_lat = $project->geo->lat;
    $project_lng = $project->geo->lng;

    if ( $project_lat != 450.0 && $project_lng != 450.0 ){
      $subscriptions = Subscription::where('sw_lat', '<', $project_lat )
        ->where('sw_lng', '<', $project_lng)
        ->where('ne_lat', '>', $project_lat)
        ->where('ne_lng', '>', $project_lng)
        ->select('user_id')
        ->distinct()
        ->get();
      foreach($subscriptions as $subscription){
        $user = User::find($subscription->user_id);
        $data = compact('user', 'project');
        Mail::queue('emails.alerts.status', $data, function($message) use ($user)
        {
          $message->to($user->email, '')->subject('[#GreenAlert] You\'ve got an update');
        });
      }
    }

    $job->delete();
  }

}