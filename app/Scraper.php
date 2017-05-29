<?php namespace AlertME;

use Illuminate\Database\Eloquent\Model;

class Scraper extends Model {

    public function scrapes()
    {
        return $this->hasMany('AlertME\Scrape');
    }

}
