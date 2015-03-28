<?php

class Scraper extends Eloquent {

    public function scrapes()
    {
        return $this->hasMany('Scrape');
    }

}