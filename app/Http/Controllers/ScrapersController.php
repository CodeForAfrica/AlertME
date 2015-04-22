<?php namespace Greenalert\Http\Controllers;

use Greenalert\Http\Controllers\scrapers\NeasPortal;
use Greenalert\Http\Requests;
use Greenalert\Http\Controllers\Controller;

use Greenalert\Scraper;
use Illuminate\Http\Request;

class ScrapersController extends Controller {

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

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        if (is_numeric($id)) {
            $scraper = Scraper::findOrFail($id);
        } else {
            $scraper = Scraper::where('slug', $id)->firstOrFail();
        }

        $scraper = $scraper->toArray() + array('scrapes' => $scraper->scrapes->toArray());

        return $scraper;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        if (is_numeric($id)) {
            $scraper = Scraper::findOrFail($id);
        } else {
            $scraper = Scraper::where('slug', $id)->firstOrFail();
        }

        $scraper = $scraper->toArray() + array('scrapes' => $scraper->scrapes->toArray());

        return $scraper;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


    public function scrape($id_or_slug)
    {
        $scraper = $this->show($id_or_slug);

        $run_scraper = camel_case($scraper['slug']);

        return $this->$run_scraper();
    }

    public function neasPortal()
    {
        $neas_portal = new NeasPortal();

        return $neas_portal->scrape();
    }

}
