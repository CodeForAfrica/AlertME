<?php namespace Greenalert;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Scrape extends Model {

    public $content = array();
    public $list_array = array();
    public $data_array = array();
    public $tsv = '';

    public function scraper()
    {
        return $this->belongsTo('Greenalert\Scraper');
    }


    public function scopeRecent($query)
    {
        return $query->where('updated_at', '<=', Carbon::now())->where('updated_at', '>=', Carbon::now()->subDay());
    }


    public function getContent()
    {
        if (!$this->id || $this->content != null) {
            // Either there is no ID or the content is set
        } else {
            $this->content = json_decode(
                \Storage::get($this->file_directory . '/' . $this->file_name . '-' . $this->id . '.json'),
                true
            );
        }

        return $this->content;
    }

    public function setContent($content = null)
    {
        if ($content == null || !is_array($content)) {
            $content = $this->getContent();
        }

        $this->content = $content;

        \Storage::put(
            $this->file_directory . '/' . $this->file_name . '-' . $this->id . '.json',
            json_encode($this->content)
        );

        if (\Storage::exists($this->file_directory . '/' . $this->file_name . '.json')) {
            \Storage::delete($this->file_directory . '/' . $this->file_name . '.json');
        };

        \Storage::copy(
            $this->file_directory . '/' . $this->file_name . '-' . $this->id . '.json',
            $this->file_directory . '/' . $this->file_name . '.json'
        );

        return $this->content;
    }

    public function saveContent($content = null)
    {
        return $this->setContent($content);
    }


    public function getListArray()
    {
        if (!$this->id) {
            return $this->list_array;
        }
        $this->list_array = json_decode(
            \Storage::get($this->file_directory . '/' . $this->file_name . '-' . $this->id . '.json'),
            true
        );

        return $this->list_array;
    }

    public function getDataArray()
    {
        if (!$this->id) {
            return $this->$data_array;
        }
        $this->$data_array = json_decode(
            \Storage::get($this->file_directory . '/' . $this->file_name . '-' . $this->id . '.json'),
            true
        );

        return $this->$data_array;
    }

    public function getTsv()
    {
        if (!$this->id) {
            return $this->tsv;
        }
        $this->saveContent();

        $tsv_array = [];
        foreach ($this->content as $key => $row) {
            $tsv_array = array_add($tsv_array, $key, array_dot($row));
        }

        $tsv_header = implode("\t", array_keys($tsv_array[0]));
        $tsv_content = '';
        foreach ($tsv_array as $row) {
            $tsv_content .= "\n";
            $tsv_content .= implode("\t", array_values($row));
        }

        $this->tsv = $tsv_header . $tsv_content;

        return $this->tsv;
    }

    public function setTsv($tsv = null)
    {
        if ($tsv == null) {
            $this->getTsv();
        } else {
            $this->tsv = $tsv;
        }

        \Storage::put($this->file_directory . '/' . $this->file_name . '-' . $this->id . '.tsv', $this->tsv);

        return $this->tsv;

    }

    public function saveTsv($tsv = null)
    {
        return $this->setTsv($tsv);
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
        return $this->file_directory . '/' . $this->file_name . '-' . $this->id . '.json';
    }


}
