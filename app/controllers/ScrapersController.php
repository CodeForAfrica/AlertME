<?php

class ScrapersController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Load Scrapers if don't exist
        $scraper = Scraper::find(1);
        if (!$scraper) {
            $scraper = new Scraper;
            $scraper->slug = 'neas-portal';
            $scraper->status = 0;
            $scraper->save();
        }


        $scrapers = Scraper::all();

        return $scrapers;
	}

    public  function show ( $id_or_slug )
    {
        if (is_numeric($id_or_slug)){
            $scraper = Scraper::findOrFail( $id_or_slug );
        } else {
            $scraper = Scraper::where( 'slug', $id_or_slug )->firstOrFail();
        }

        return $scraper;
    }

    public function scrape ( $id_or_slug )
    {
        $scraper = Scraper::find($id_or_slug);

        return $scraper;
    }


}
