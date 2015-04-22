<?php namespace Greenalert;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Scrape extends Model {

    public $csv = '';
    public $csv_array = array();
    public $csv_headers = array();

    public function scraper()
    {
        return $this->belongsTo('Greenalert\Scraper');
    }


    public function scopeRecent($query)
    {
        return $query->where('updated_at', '<=', Carbon::now())->where('updated_at', '>=', Carbon::now()->subDay());
    }


    public function getCsv()
    {
        if (!$this->id) {
            return $this->csv;
        }
        $this->csv = \Storage::get($this->file_directory . '/' . $this->file_name . '-' . $this->id . '.csv');
        $csv_rows = explode("\n", $this->csv);
        foreach ($csv_rows as $csv_row_i => $csv_row) {
            $csv_row = explode(',', $csv_row);
            foreach ($csv_row as $csv_item_i => $csv_item) {
                $this->csv_array[ $csv_row_i ][ $csv_item_i ] = $csv_item;
            }
        }

        return $this->csv;
    }

    public function getCsvArray()
    {
        if (!$this->id) {
            return $this->$csv_array;
        }
        $this->csv = \Storage::get($this->file_directory . '/' . $this->file_name . '-' . $this->id . '.csv');
        $csv_rows = explode("\n", $this->csv);
        foreach ($csv_rows as $csv_row_i => $csv_row) {
            $csv_row = explode(',', $csv_row);
            foreach ($csv_row as $csv_item_i => $csv_item) {
                $this->csv_array[ $csv_row_i ][ $csv_item_i ] = $csv_item;
            }
        }

        return $this->$csv_array;
    }

    public function setCsv($csv = null)
    {
        if (is_null($csv)) {
            $csv = $this->csv;
        }

        $csv = 'We\'ve set the csv';

        return $csv;
    }

    public function file_path()
    {
        return $this->file_directory . '/' . $this->file_name . '-' . $this->id . '.csv';
    }


}
