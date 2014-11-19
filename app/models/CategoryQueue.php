<?php

class CategoryQueue {

  public function fire($job, $data)
  {
    Log::info('['.$job->getJobId().':'.$job->attempts().'] Category assignment started.');

    $category = Category::find($data['cat_id']);
    $projects = Project::all();
    foreach ($projects as $project)
    {
      $project->assignCategory($category);
    }

    Log::info('['.$job->getJobId().':'.$job->attempts().'] Category assignment completed.');

    $job->delete();

  }

}
