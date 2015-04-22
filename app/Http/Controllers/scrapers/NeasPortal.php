<?php namespace Greenalert\Http\Controllers\scrapers;

use Greenalert\Http\Requests;
use Greenalert\Http\Controllers\Controller;

use Greenalert\Scrape;
use Greenalert\Scraper;

class NeasPortal extends Controller {

    public $scraper;
    public $scrape;

    public $client;

    public $neas_list_url;
    public $neas_eia_url;

    function __construct()
    {

        $this->scraper = Scraper::find(1);
        $this->scrape = new Scrape;

        $this->scrape->scraper()->associate($this->scraper);

        $this->scrape->csv = '';
        $this->scrape->csv_array = array();
        $this->scrape->csv_headers = array();

        $this->scrape->file_directory = 'scrapes';
        $this->scrape->file_name = 'neas_portal';

        $this->client = new \Goutte\Client();

        $this->client->getClient()->setDefaultOption('config/curl/' . CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        $this->client->getClient()->setDefaultOption('config/curl/' . CURLOPT_TIMEOUT, 0);
        $this->client->getClient()->setDefaultOption('config/curl/' . CURLOPT_TIMEOUT_MS, 0);
        $this->client->getClient()->setDefaultOption('config/curl/' . CURLOPT_CONNECTTIMEOUT, 0);
        $this->client->getClient()->setDefaultOption('config/curl/' . CURLOPT_RETURNTRANSFER, true);

        $this->neas_list_url = 'http://neas.environment.gov.za/portal/ApplicationsPerEAP_Report.aspx';
        $this->neas_eia_url = 'http://neas.environment.gov.za/portal/dNeasStandard_ApplicationHistory.aspx';

    }


    function scrape_run()
    {
        set_time_limit(0);

        \Log::info('SCRAPER [' . $this->scraper->slug . ']: Scrape started.');

        $this->scrape_list();
        $this->scrape_eias();

        \Log::info('SCRAPER [' . $this->scraper->slug . ']: Scrape completed!');

        return 0;

    }


    function scrape_list()
    {
        \Log::info('SCRAPER [' . $this->scraper->slug . ']: Scrape list started.');

        $crawler = $this->client->request('GET', $this->neas_list_url);

        $form = $crawler->selectButton('ctl00$Content$Search')->form();
        $crawler = $this->client->submit($form, array('ctl00$Content$txtName' => ''));

        \Log::info('SCRAPER [' . $this->scraper->slug . ']: Download complete.');

        $crawler->filter('table#ctl00_Content_gv tr')->first()->filter('th')->each(function ($node, $i) {
            $this->scrape->csv_headers[0][ $i ] = $node->text();
        });
        $crawler->filter('table#ctl00_Content_gv tr')->each(function ($node, $i) {
            $node->filter('td')->each(function ($node_1, $i_1) use ($i) {
                $this->scrape->csv_array[ $i ][ $i_1 ] = $node_1->text();
            });
        });

        $this->scrape->csv_array = $this->scrape->csv_headers + $this->scrape->csv_array;

        foreach ($this->scrape->csv_array as $row) {
            $this->scrape->csv .= implode(',', $row) . "\n";
        }

        \Storage::put($this->scrape->file_directory . '/' . $this->scrape->file_name . '.csv', $this->scrape->csv);

        $this->scrape->save();

        \Storage::copy(
            $this->scrape->file_directory . '/' . $this->scrape->file_name . '.csv',
            $this->scrape->file_directory . '/' . $this->scrape->file_name . '-' . $this->scrape->id . '.csv'
        );

        \Log::info('SCRAPER [' . $this->scraper->slug . ']: Scrape list completed.');

        return $this;

    }


    function scrape_eias()
    {
        \Log::info('SCRAPER [' . $this->scraper->slug . ']: Scrape EIAs started.');

        for ($i = 1; $i <= 10; $i++) {
            $eia_id = $this->scrape->csv_array[ $i ][2];

            $crawler = $this->client->request('GET', $this->neas_eia_url);

            $form = $crawler->selectButton('ctl00$Content$SearchID')->form();
            $crawler = $this->client->submit($form, array('ctl00$Content$txtPermitNumber' => $eia_id));

            $form = $crawler->selectButton('ctl00$Content$btnViewReport')->form();
            $crawler = $this->client->submit($form, array('ctl00$Content$txtPermitNumber' => $eia_id));

            \Log::info('SCRAPER [' . $this->scraper->slug . ']: Scrape EIAs something started.');

            dd($crawler->filter('#ctl00_Content_lblProjectDescription')->first()->text());

        }

        \Log::info('SCRAPER [' . $this->scraper->slug . ']: Scrape EIAs completed.');

        return 'Awesome';

    }

}
