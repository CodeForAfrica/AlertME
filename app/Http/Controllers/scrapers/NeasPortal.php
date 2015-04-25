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

    public $eias = [];

    function __construct()
    {

        $this->scraper = Scraper::find(1);
        $this->scrape = new Scrape;

        $this->scrape->scraper()->associate($this->scraper);

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

        if (!$this->scraped_recently()) {
            $this->scrape_list();
        } else {
            $this->scrape = $this->scraper->scrapes->last(1);
            $this->scrape->getCsv();
        }

        $this->scrape_eias();

        \Log::info('SCRAPER [' . $this->scraper->slug . ']: Scrape completed!');

        return 0;

    }


    function scraped_recently()
    {
        $scrapes = $this->scraper->scrapes;
        if (!$scrapes) {
            return false;
        }
        if ($scrapes->last()->recent()->count() == 0) {
            return false;
        }

        return true;
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

        for ($i = 1; $i < count($this->scrape->csv_array); $i++) {
            $eia_id = $this->scrape->csv_array[ $i ][2];

            $crawler = $this->client->request('GET', $this->neas_eia_url);

            $form = $crawler->selectButton('ctl00$Content$SearchID')->form();
            $crawler = $this->client->submit($form, array('ctl00$Content$txtPermitNumber' => $eia_id));

            $form = $crawler->selectButton('ctl00$Content$btnViewReport')->form();
            $crawler = $this->client->submit($form, array('ctl00$Content$txtPermitNumber' => $eia_id));


            $this->eias[ $i - 1 ]['NEAS Number'] = $this->scrape->csv_array[ $i ][2];

            $this->eias[ $i - 1 ]['Name'] = $this->scrape->csv_array[ $i ][0];
            $this->eias[ $i - 1 ]['Person Type'] = $this->scrape->csv_array[ $i ][1];

            $this->eias[ $i - 1 ]['Provincial Ref No.'] = $this->scrape->csv_array[ $i ][3];
            $this->eias[ $i - 1 ]['File Reference Number'] = $this->scrape->csv_array[ $i ][4];
            $this->eias[ $i - 1 ]['Application Type'] = $this->scrape->csv_array[ $i ][5];
            $this->eias[ $i - 1 ]['Competent Auuthority'] = $this->scrape->csv_array[ $i ][6];
            $this->eias[ $i - 1 ]['Stage'] = $this->scrape->csv_array[ $i ][7];
            $this->eias[ $i - 1 ]['Status'] = $this->scrape->csv_array[ $i ][8];
            $this->eias[ $i - 1 ]['Capturer'] = $this->scrape->csv_array[ $i ][9];
            $this->eias[ $i - 1 ]['Case Officer'] = $this->scrape->csv_array[ $i ][10];


            $this->eias[ $i - 1 ]['Local Municipality'] = $crawler->filter('#ctl00_Content_lblLocal')->first()->text();
            $this->eias[ $i - 1 ]['Application Process'] = $crawler->filter('#ctl00_Content_lblProcess')->first()->text();

            $this->eias[ $i - 1 ]['Project Description'] = $crawler->filter('#ctl00_Content_lblProjectDescription')->first()->text();
            $this->eias[ $i - 1 ]['Project Title'] = $crawler->filter('#ctl00_Content_lblProjectTitle')->first()->text();
            $this->eias[ $i - 1 ]['Property Name'] = $crawler->filter('#ctl00_Content_lblPropertyName')->first()->text();
            $this->eias[ $i - 1 ]['SGID'] = $crawler->filter('#ctl00_Content_lblSGID')->first()->text();

            $this->eias[ $i - 1 ]['Applicant'] = [];

            $crawler->filter('tr#ctl00_Content_trViewReport table#ctl00_Content_dgApplicant > tr')->each(function ($node, $i_1) use ($i) {

                if ($i_1 != 0 && $node->children()->eq(0)->text() !== '') {
                    $this->eias[ $i - 1 ]['Applicant'][ $i_1 - 1 ] = [];

                    $this->eias[ $i - 1 ]['Applicant'][ $i_1 - 1 ]['Applicant Type'] = $node->children()->eq(0)->text();
                    $this->eias[ $i - 1 ]['Applicant'][ $i_1 - 1 ]['Applicant Name'] = $node->children()->eq(1)->text();
                    $this->eias[ $i - 1 ]['Applicant'][ $i_1 - 1 ]['Registration Number'] = $node->children()->eq(2)->text();
                    $this->eias[ $i - 1 ]['Applicant'][ $i_1 - 1 ]['Telephone Number'] = $node->children()->eq(3)->text();
                }
            });

            $this->eias[ $i - 1 ]['History'] = [];

            $crawler->filter('tr#ctl00_Content_trViewReport table#ctl00_Content_dgHistory > tr')->each(function ($node, $i_1) use ($i) {

                if ($i_1 != 0) {
                    $this->eias[ $i - 1 ]['History'][ $i_1 - 1 ] = [];

                    $this->eias[ $i - 1 ]['History'][ $i_1 - 1 ]['Stage'] = $node->children()->eq(0)->text();
                    $this->eias[ $i - 1 ]['History'][ $i_1 - 1 ]['Assigned Capturer'] = $node->children()->eq(1)->text();
                    $this->eias[ $i - 1 ]['History'][ $i_1 - 1 ]['Date System Recorded the Action'] = $node->children()->eq(2)->text();
                    $this->eias[ $i - 1 ]['History'][ $i_1 - 1 ]['Stage Case Officer'] = $node->children()->eq(3)->text();
                }

            });

        }

        \Storage::put($this->scrape->file_directory . '/' . $this->scrape->file_name . '.json', json_encode($this->eias));

        \Storage::copy(
            $this->scrape->file_directory . '/' . $this->scrape->file_name . '.json',
            $this->scrape->file_directory . '/' . $this->scrape->file_name . '-' . $this->scrape->id . '.json'
        );

        \Log::info('SCRAPER [' . $this->scraper->slug . ']: Scrape EIAs completed.');

        return 'Awesome';

    }

}
