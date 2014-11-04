<?php

class CategoryQueue {

  public function fire($job, $data)
  {
    Log::info('['.$job->getJobId().':'.$job->attempts().'] Category assignment started.');

    $data_obj = json_decode(json_encode($data), FALSE);
    $cat_id = $data_obj->cat_id;
    $cat_old = $data_obj->cat_old;
    $cat_new = $data_obj->cat_new;

    if ( $data_obj->new == TRUE ) {
      // New Category
      // Assign if is not empty keywords
      if ( $cat_new->keywords != '' ) {
        self::keywordAssign( $cat_new );
      }

    } else {

      // Category update
      if ( $cat_new->keywords != $cat_old->keywords ){
        // If old + new keywords don't match, Unassign
        Category::keywordUnAssign( $cat_new );

        if ( $cat_new->keywords != '' && $cat_new->keywords != NULL ) {
          // If not empty keywords, assign
          self::keywordAssign( $cat_new );
        }
      }
    }

    Log::info('['.$job->getJobId().':'.$job->attempts().'] Category assignment completed.');

    $job->delete();

  }

  function keywordAssign ( $category )
  {
    $keywords = explode(",", $category->keywords);

    $projects = Project::all();

    foreach ($projects as $project)
    {
      //
      $assign_cat = false;
      foreach ( $keywords as $keyword ) {
        $in_title  = stripos( $project->title, $keyword );
        $in_desc   = stripos( $project->description, $keyword );
        $in_sector = stripos( $project->status, $keyword );

        // If keyword found
        if ($in_title !== false || $in_desc !== false || $in_sector !== false) {
          $assign_cat = true;
        }
      }
      if ($assign_cat) {
        DB::table('project_category')->insert(
          array(
            'project_id' => $project->id,
            'category_id' => $category->id
          )
        );
      }
    }
  }

}
