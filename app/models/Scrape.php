<?php

class Scrape extends Eloquent {

    public function scraper()
    {
        return $this->belongsTo('Scraper');
    }

}