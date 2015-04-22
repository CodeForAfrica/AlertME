<?php namespace Greenalert;

use Illuminate\Database\Eloquent\Model;

class Scrape extends Model {

    public $csv = '';
    public $csv_array = array();
    public $csv_headers = array();

    public function scraper()
    {
        return $this->belongsTo('Greenalert\Scraper');
    }

    public function getCsv()
    {
        return 'An empty csv';
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
        return $this->file_location . '/' . $this->file_name;
    }

}
