<?php

class AlertQueue {

  public function fire($job, $data)
  {
    // Log::info('['.$job->getJobId().':'.$job->attempts().'] Alert happening.');

    $alert = Alert::find($data['alert_id']);
    if($alert->status != 0) {
      $job->delete();
      return;
    } else {
      $alert->status = 1;
      $alert->save();
    }

    $project = Project::find($alert->project_id);
    $project_lat = 450.0;
    $project_lng = 450.0;

    if( $project->geo_type = 'lat_lng' ){
      $project_lat = $project->geo_lat;
      $project_lng = $project->geo_lng;
    }
    if( $project->geo_type = 'address' && $project->geo_address != '' ){
      $geocode = Geocode::where('address', $project->geo_address)->first();
      if($geocode->status == 1){
        $project_lat = $geocode->lat;
        $project_lng = $geocode->lng;
      }
    }

    if ( $project_lat != 450.0 && $project_lng != 450.0 ){
      $subscriptions = AlertRegistration::where('sw_lat', '<', $project_lat )
      ->where('sw_lng', '<', $project_lng)
      ->where('ne_lat', '>', $project_lat)
      ->where('ne_lng', '>', $project_lng)
      ->get();
      foreach($subscriptions as $subscription){
        $GLOBALS['alert_user'] = AlertUser::find($subscription->alert_user_id);
        Mail::send('emails.alert', array('project_id' => $project->project_id), function($message)
        {
          $message->from('greenalert@codeforafrica.org', '#GreenAlert');
          $message->to($GLOBALS['alert_user']->email, '')->subject('#GreenAlert Alert');
        });
      }
    }

    $job->delete();
  }

}
