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
      $data = $datasourcedata->datasource->fetchData();
      if(!$data) {
        return false;
      }
      $datasourcedata->headers = array_keys($data[0]);
      $datasourcedata->raw = $data;

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
    return $this->belongsTo('Datasource');
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

}
