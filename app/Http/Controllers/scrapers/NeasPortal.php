<?php namespace Greenalert\Http\Controllers\scrapers;

use Greenalert\Http\Requests;
use Greenalert\Http\Controllers\Controller;

use Greenalert\Scrape;
use Greenalert\Scraper;
use Illuminate\Http\Request;

class NeasPortal extends Controller {

    function scrape()
    {
        set_time_limit(0);

        $scraper = Scraper::find(1);

        $scrape = new Scrape;
        $scrape->scraper()->associate($scraper);

        $scrape->csv = '';
        $scrape->csv_array = array();
        $scrape->csv_headers = array();

        $scrape->file_directory = 'scrapes';
        $scrape->file_name = 'neas_portal';

        $neas_url = 'http://neas.environment.gov.za/portal/ApplicationsPerEAP_Report.aspx';

        \Log::info('SCRAPER [' . $scraper->slug . ']: Scrape started.');

        $client = new \Goutte\Client();

        $client->getClient()->setDefaultOption('config/curl/' . CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        $client->getClient()->setDefaultOption('config/curl/' . CURLOPT_TIMEOUT, 0);
        $client->getClient()->setDefaultOption('config/curl/' . CURLOPT_TIMEOUT_MS, 0);
        $client->getClient()->setDefaultOption('config/curl/' . CURLOPT_CONNECTTIMEOUT, 0);
        $client->getClient()->setDefaultOption('config/curl/' . CURLOPT_RETURNTRANSFER, true);

        $crawler = $client->request('GET', $neas_url);

        \Log::info('SCRAPER [' . $scraper->slug . ']: Download complete.');

        $form = $crawler->selectButton('ctl00$Content$Search')->form();
        $crawler = $client->submit($form, array('ctl00$Content$txtName' => ''));

        $crawler->filter('table#ctl00_Content_gv tr')->first()->filter('th')->each(function ($node, $i) use ($scrape) {
            $scrape->csv_headers[0][ $i ] = $node->text();
        });
        $crawler->filter('table#ctl00_Content_gv tr')->each(function ($node, $i) use ($scrape) {
            $node->filter('td')->each(function ($node_1, $i_1) use ($scrape, $i) {
                $scrape->csv_array[ $i ][ $i_1 ] = $node_1->text();
            });
        });

        $scrape->csv_array = $scrape->csv_headers + $scrape->csv_array;

        foreach ($scrape->csv_array as $row) {
            $scrape->csv .= implode(',', $row) . "\n";
        }

        \Storage::put($scrape->file_directory . '/' . $scrape->file_name . '.csv', $scrape->csv);

        $scrape->save();

        \Storage::copy(
            $scrape->file_directory . '/' . $scrape->file_name . '.csv',
            $scrape->file_directory . '/' . $scrape->file_name . '-' . $scrape->id . '.csv'
        );

        

        \Log::info('SCRAPER [' . $scraper->slug . ']: Scrape complete.');

        return response()->download(storage_path() . '/app/' . $scrape->file_directory . '/' . $scrape->file_name . '.csv');

    }

}
