<?php namespace Greenalert;

use Greenalert\Commands\DataSourceQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

class DataSource extends Model {

    /**
     * CONFIG STATUS
     * -------------
     * 0: Failed to configure
     * 1: Configured successfully
     * 2: Ready to configure
     * 3: Fetching columns
     * 4:
     */

    public static function boot()
    {
        parent::boot();

        // Setup event bindings...
        DataSource::created(function ($datasource) {
            \Queue::push(new DataSourceQueue($datasource->id));
        });

        DataSource::deleting(function ($datasource) {
            DataSourceSync::where('datasource_id', $datasource->id)->delete();

            $projects = $datasource->projects;
            foreach ($projects as $project) {
                $project->delete();
            }
            Storage::deleteDirectory("public/photos/$datasource->id");
        });
    }


    // Accessors & Mutators

    public function getConfigAttribute($value)
    {
        return json_decode($value);
    }

    public function setConfigAttribute($value)
    {
        $this->attributes['config'] = json_encode($value);
    }

    public function getColumnsAttribute($value)
    {
        return json_decode($value);
    }

    public function setColumnsAttribute($value)
    {
        $this->attributes['columns'] = json_encode($value);
    }


    // Relationships

    public function datasourcesync()
    {
        return $this->hasMany('Greenalert\DataSourceSync', 'datasource_id');
    }

    public function projects()
    {
        return $this->hasMany('Greenalert\Project', 'datasource_id');
    }


    // Other functions

    function syncData($sync)
    {
        // Add DataSource Sync
        $ds_sync = new DataSourceSync;
        $ds_sync->sync_id = $sync->id;
        $ds_sync->datasource_id = $this->id;
        // TODO: Check if new datasource
        if (true) {
            $ds_sync->sync_status = 2; // Old Data Source Sync
        } else {
            $ds_sync->sync_status = 3; // New Data Source Sync
        }
        $ds_sync->save();

        // Fetch Data
        $csv = $this->fetch();
        \Log::info('Download completed.');
        if (!$csv) {
            $ds_sync->sync_status = 3;
            $ds_sync->save();

            return false;
        }

        // Check column change
        if (array_keys($csv[0]) != $this->columns) {
            \Log::info('Columns are different from configuration.');

            $ds_sync->sync_status = 4;
            $ds_sync->save();

            $this->columns = array_keys($csv[0]);
            $this->status->col = 2;
            $this->save();

            // TODO: Email column change - needs configuration before sync

            return false;
        }

        // Set Projects from data fetched
        $this->setProjects($csv, $ds_sync);
        \Log::info('Projects update completed.');

        // TODO: Send alerts created from sync

        $ds_sync->sync_status = 1;
        $ds_sync->save();

        return true;
    }

    function setProjects($csv, $ds_sync)
    {
        $cols = $this->columns; // String Keys
        $config = $this->config; // Integer position

        $categories = Category::all();

        foreach ($csv as $row) {
            $project = Project::firstOrCreate(array(
                'data_id' => $row[ $cols[ $config->id->col ] ]
            ));

            $project->datasource_id = $this->id;
            $project->data_source_sync_id = $ds_sync->id;

            $project->title = $row[ $cols[ $config->title->col ] ];

            $project->description = $row[ $cols[ $config->desc->col ] ];

            if ($config->geo->type == 'address') {
                $project->geo_type = 'address';
                $project->geo_address = $row[ $cols[ $config->geo->address->col ] ];
            }
            if ($config->geo->type == 'lat_lng') {
                $project->geo_type = 'lat_lng';
                $project->geo_lat = $row[ $cols[ $config->geo->lat_lng->lat->col ] ];
                $project->geo_lng = $row[ $cols[ $config->geo->lat_lng->lng->col ] ];
            }

            $project->status = $row[ $cols[ $config->status->col ] ];

            $project->data = $row;

            $project = $this->downloadPhotos($project);

            foreach ($categories as $category) {
                $project->assignCategory($category);
            }

            $project->save();
        }
    }


    // TODO: Rename this function - Conflict
    public function fetch()
    {
        // Validate URL + Headers
        if (!filter_var($this->url, FILTER_VALIDATE_URL)) {
            // Not a Valid URL
            \Log::error("DataSource Fetch: Not a valid URL $this->url.");

            return false;
        } else {
            // Is a Valid URL
            $file_headers = @get_headers($this->url);

            if ($file_headers[0] == 'HTTP/1.0 404 Not Found') {
                // echo "The file $filename does not exist";
                \Log::error("DataSource Fetch: The file $this->url does not exist.");

                return false;
            } else if ($file_headers[0] == 'HTTP/1.0 302 Found' && $file_headers[7] == 'HTTP/1.0 404 Not Found') {
                // echo "The file $filename does not exist, and I got redirected to a custom 404 page.";
                \Log::error("DataSource Fetch: The file $this->url does not exist, and I got redirected to a custom 404 page.");

                return false;
            }
        }

        // Return array of csv
        return $this->csv_to_array($this->url);
    }


    // TODO: Do this in queue
    function downloadPhotos($project)
    {
        Log::info("[$project->datasource_id:$project->id] Photo download started.");

        $data = $project->data;

        $index = 0;
        foreach ($data as $col => $value) {
            if (str_contains(strtolower($col), 'photo')) {
                try {
                    $photo_location = "/photos/$project->datasource_id/$project->id-$index";
                    Storage::put("public".$photo_location, file_get_contents($value));
                    $data[$col] = "/storage".$photo_location;
                    Log::info("[$project->datasource_id:$project->id] Photo download successful.");
                } catch (Exception $e) {
                    Log::error("[$project->datasource_id:$project->id] Photo download failed (".$value.").");
                }
            }
            $index++;
        }

        $project->data = $data;

        return $project;
    }


    /**
     * Convert a comma separated file into an associated array.
     * The first row should contain the array keys.
     *
     * Example:
     *
     * @param string $filename  Path to the CSV file
     * @param string $delimiter The separator used in the file
     * @return array
     * @link      http://gist.github.com/385876
     * @author    Jay Williams <http://myd3.com/>
     * @copyright Copyright (c) 2010, Jay Williams
     * @license   http://www.opensource.org/licenses/mit-license.php MIT License
     */
    function csv_to_array($filename = '', $delimiter = ',')
    {
        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                $row = array_map('trim', $row);
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

}
