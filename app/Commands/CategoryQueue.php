<?php namespace AlertME\Commands;

use AlertME\Category;
use AlertME\Commands\Command;

use AlertME\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class CategoryQueue extends Command implements SelfHandling, ShouldQueue {

    use InteractsWithQueue, SerializesModels;

    public $cat_id;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($cat_id)
    {
        $this->cat_id = $cat_id;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('[' . $this->job->getJobId() . ':' . $this->attempts() . '] Category assignment started.');

        $category = Category::find($this->cat_id);
        $projects = Project::all();
        foreach ($projects as $project) {
            $project->assignCategory($category);
        }

        \Log::info('[' . $this->job->getJobId() . ':' . $this->attempts() . '] Category assignment completed.');

    }

}
