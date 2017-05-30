<?php namespace AlertME\Commands;

use AlertME\DataSource;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class DataSourceQueue extends Command implements ShouldQueue {

    use InteractsWithQueue, SerializesModels;

    public $datasource_id;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($datasource_id)
    {
        $this->datasource_id = $datasource_id;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('[' . $this->job->getJobId() . ':' . $this->attempts() . '] Fetch datasource columns started.');

        $datasource = DataSource::find($this->datasource_id);

        if (!$datasource) {
            return;
        }
        if ($this->attempts() > 3) {
            $datasource->config_status = 0;
            $datasource->save();

            \Log::info('[' . $this->job->getJobId() . ':' . $this->attempts() . '] Fetch datasource columns failed.');

            return;
        }

        // TODO: Rename fetch function
        $ds_data = $datasource->fetch();

        if (!$ds_data) {
            $datasource->config_status = 0;
            $datasource->save();
        } else {
            $datasource->columns = array_keys($ds_data[0]);
            $datasource->config_status = 2;
            $datasource->save();
        }

        \Log::info('[' . $this->job->getJobId() . ':' . $this->attempts() . '] Fetch datasource columns successful.');

    }

}
