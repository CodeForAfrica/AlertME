<?php

class DataSourceData extends Eloquent {

  protected $table = 'data_source_datas';
  protected $fillable = array('datasource_id');


  public static function boot()
  {
    parent::boot();

    // Setup event bindings...
    DataSourceData::creating(function($datasourcedata)
    {
      

    });

    DataSourceData::created(function($datasourcedata)
    {
      
    });
  }


  // Accessors & Mutators

  public function getHeadersAttribute($value)
  {
      return json_decode($value);
  }

  public function setHeadersAttribute($value)
  {
    $this->attributes['headers'] = json_encode($value);
  }

  public function getRawAttribute($value)
  {
      return json_decode($value);
  }

  public function setRawAttribute($value)
  {
    $this->attributes['raw'] = json_encode($value);
  }


  // Realtionships

  public function datasource()
  {
    return $this->belongsTo('DataSource');
  }

  public function projects()
  {
    return $this->hasMany('Project');
  }


  // Other functions

  public function fetch()
  {
    // Validate URL + Headers
    if(!filter_var($this->datasource->url, FILTER_VALIDATE_URL))
    {
      // Not a Valid URL
      return false;
    } else {
      // Is a Valid URL
      $file_headers = @get_headers($this->datasource->url);

      if($file_headers[0] == 'HTTP/1.0 404 Not Found'){
        // echo "The file $filename does not exist";
        return false;
      } else if ($file_headers[0] == 'HTTP/1.0 302 Found' && $file_headers[7] == 'HTTP/1.0 404 Not Found'){
        // echo "The file $filename does not exist, and I got redirected to a custom 404 page..";
        return false;
      }
    }

    // Validate File Exists
    if (! file_exists ( $this->datasource->url)) return false;

    // Return array of csv
    return $this->csv_to_array($this->datasource->url);
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
