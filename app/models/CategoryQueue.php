<?php

class CategoryQueue {

  public function fire($job, $data)
  {
    Log::info('['.$job->getJobId().':'.$job->attempts().'] Category assignment started.');

    $category = Category::find($data['cat_id']);
    $category->keywordAssign();

    Log::info('['.$job->getJobId().':'.$job->attempts().'] Category assignment completed.');

    $job->delete();

  }

}
