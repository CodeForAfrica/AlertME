<?php

class NeasPortal extends \BaseController {

    function scrape()
    {
        set_time_limit(0);

        $scraper = Scraper::find(1);

        $scrape = new Scrape;
        $scrape->scraper()->associate($scraper);

        $scrape->csv = '';
        $scrape->csv_array = array();
        $scrape->csv_headers = array();

        $scrape->file_location = storage_path() . '/scrapes/';
        $scrape->file_name = 'neas_portal.csv';

        $neas_url = 'http://neas.environment.gov.za/portal/ApplicationsPerEAP_Report.aspx';

        $client = new Goutte\Client();

        $client->getClient()->setDefaultOption('config/curl/' . CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        $client->getClient()->setDefaultOption('config/curl/' . CURLOPT_TIMEOUT, 0);
        $client->getClient()->setDefaultOption('config/curl/' . CURLOPT_TIMEOUT_MS, 0);
        $client->getClient()->setDefaultOption('config/curl/' . CURLOPT_CONNECTTIMEOUT, 0);
        $client->getClient()->setDefaultOption('config/curl/' . CURLOPT_RETURNTRANSFER, true);

        $crawler = $client->request('GET', $neas_url);

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

        File::put($scrape->file_location . $scrape->file_name, $scrape->csv);

        $scrape->save();

        return Response::download($scrape->file_location . $scrape->file_name);

    }

}
