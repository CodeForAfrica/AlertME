<?php

class GeocodeQueue {

  public function fire($job, $data)
  {
    if ($job->attempts() > 3)
    {
        $job->delete();
    }

    $project = Project::find( $data['project_id'] );

    if ( trim($project->geo_address) != '' && $project->geo_address != NULL )
    {
      $geocode = Geocode::firstOrCreate( array(
        'address' => $project->geo_address
      ));

      if( $geocode->status == 0 ) {
        $geocode = Geocode::fetchGeo( $geocode );
        $geocode->save();
      }

      if( $geocode->status == 1 ) {
        $project->geo_lat = $geocode->lat;
        $project->geo_lng = $geocode->lng;
      }

      $project->save();
    }

    $job->delete();
  }

}
