<?php

class DataSourceData extends Eloquent {

    protected $table = 'data_source_datas';
    protected $fillable = array('data_source_id');


    public static function boot()
    {
      parent::boot();

      // Setup event bindings...
      DataSourceData::creating(function($ds_data)
      {
        $data = $ds_data->fetchData();
        if(!$data) {
          return false;
        }

        $ds_data->headers = json_encode(array_keys($data[0]));
        $ds_data->raw = json_encode($data);

      });

      DataSourceData::created(function($ds_data)
      {

      });
    }

    public function datasource()
    {
      return $this->belongsTo('DataSource');
    }

    public function projects()
    {
      return $this->hasMany('Project');
    }


    public function setData()
    {
      if (!Schema::hasTable('data_source_datas_'.$this->data_source_id))
      {
        //
        Schema::create('data_source_datas_'.$this->data_source_id, function($table)
        {
          $table->increments('id');
          $table->string('data_id')->default('0');
          $table->longText('data')->nullable();
          $table->timestamps();
        });

        Schema::table('data_source_datas_'.$this->data_source_id, function($table)
        {
          $table->index('data_id');
        });
      }

      $ds_config = DataSourceConfig::where('data_source_id', '=', $this->data_source_id)->first();
      $ds_cols = json_decode($ds_config->data_source_columns);
      $config = json_decode($ds_config->config); // Integer position
      $datas = json_decode($this->raw, true);
      
      foreach($datas as $data){
        $ds_data = DB::table('data_source_datas_'.$this->data_source_id)
          ->where('data_id', $data[ $ds_cols[ $config->config_id ] ])->first();
        if(!$ds_data) {
          DB::table('data_source_datas_'.$this->data_source_id)->insert(
            array('data_id' => $data[ $ds_cols[ $config->config_id ] ])
          );
        }
        DB::table('data_source_datas_'.$this->data_source_id)
          ->where('data_id', $data[ $ds_cols[ $config->config_id ] ])
          ->update(array(
            'data' => json_encode($data)
          ));
      }
    }

    public function fetchData()
    {
      $datasource = DataSource::find($this->data_source_id);

      if(!filter_var($datasource->url, FILTER_VALIDATE_URL))
      {
        // Not a URL
        if (! file_exists ( $datasource->url)) return false;
      }
      else
      {
        // Is a URL
        $file_headers = @get_headers($datasource->url);

        if($file_headers[0] == 'HTTP/1.0 404 Not Found'){
          // echo "The file $filename does not exist";
          return false;
        } else if ($file_headers[0] == 'HTTP/1.0 302 Found' && $file_headers[7] == 'HTTP/1.0 404 Not Found'){
          // echo "The file $filename does not exist, and I got redirected to a custom 404 page..";
          return false;
        }
      }

      $data = DataSourceData::csv_to_array($datasource->url);

      return $data;
    }


    /**
     * Convert a comma separated file into an associated array.
     * The first row should contain the array keys.
     *
     * Example:
     *
     * @param string $filename Path to the CSV file
     * @param string $delimiter The separator used in the file
     * @return array
     * @link http://gist.github.com/385876
     * @author Jay Williams <http://myd3.com/>
     * @copyright Copyright (c) 2010, Jay Williams
     * @license http://www.opensource.org/licenses/mit-license.php MIT License
     */
    function csv_to_array($filename='', $delimiter=',')
    {

    	$header = NULL;
    	$data = array();
    	if (($handle = fopen($filename, 'r')) !== FALSE)
    	{
    		while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE)
    		{
          $row = array_map('trim', $row);
    			if(!$header)
    				$header = $row;
    			else
    				$data[] = array_combine($header, $row);
    		}
    		fclose($handle);
    	}
    	return $data;
    }

}
