<?php namespace Greenalert;

use Illuminate\Database\Eloquent\Model;

class Scraper extends Model {

    public function scrapes()
    {
        return $this->hasMany('Greenalert\Scrape');
    }

}
