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
            $this->scrape->getListArray();
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

        $crawler->filter('table#ctl00_Content_gv > tr')->each(function ($node, $i) {

            if ($i != 0) {
                $this->scrape->list_array[ $i - 1 ]['NEAS Number'] = $node->children()->eq(2)->text();

                $this->scrape->list_array[ $i - 1 ]['Name'] = $node->children()->eq(0)->text();
                $this->scrape->list_array[ $i - 1 ]['Person Type'] = $node->children()->eq(1)->text();

                $this->scrape->list_array[ $i - 1 ]['Provincial Ref No.'] = $node->children()->eq(3)->text();
                $this->scrape->list_array[ $i - 1 ]['File Reference Number'] = $node->children()->eq(4)->text();
                $this->scrape->list_array[ $i - 1 ]['Application Type'] = $node->children()->eq(5)->text();
                $this->scrape->list_array[ $i - 1 ]['Competent Auuthority'] = $node->children()->eq(6)->text();
                $this->scrape->list_array[ $i - 1 ]['Stage'] = $node->children()->eq(7)->text();
                $this->scrape->list_array[ $i - 1 ]['Status'] = $node->children()->eq(8)->text();
                $this->scrape->list_array[ $i - 1 ]['Capturer'] = $node->children()->eq(9)->text();
                $this->scrape->list_array[ $i - 1 ]['Case Officer'] = $node->children()->eq(10)->text();
            }
        });

        \Storage::put(
            $this->scrape->file_directory . '/' . $this->scrape->file_name . '-' . $this->scrape->id . '-list.json',
            json_encode($this->scrape->list_array)
        );

        $this->scrape->save();

        \Storage::copy(
            $this->scrape->file_directory . '/' . $this->scrape->file_name . '-' . $this->scrape->id . '-list.json',
            $this->scrape->file_directory . '/' . $this->scrape->file_name . '-list.json'
        );

        $this->scrape->setTsv();

        \Log::info('SCRAPER [' . $this->scraper->slug . ']: Scrape list completed.');

        return $this;

    }


    function scrape_eias()
    {
        \Log::info('SCRAPER [' . $this->scraper->slug . ']: Scrape EIAs started.');


        for ($i = 0; $i < 10; $i++) {
            $eia_id = $this->scrape->list_array[ $i ]['NEAS Number'];

            $crawler = $this->client->request('GET', $this->neas_eia_url);

            $form = $crawler->selectButton('ctl00$Content$SearchID')->form();
            $crawler = $this->client->submit($form, array('ctl00$Content$txtPermitNumber' => $eia_id));

            $form = $crawler->selectButton('ctl00$Content$btnViewReport')->form();
            $crawler = $this->client->submit($form, array('ctl00$Content$txtPermitNumber' => $eia_id));

            $this->eias [ $i ] = $this->scrape->list_array[ $i ];


            $this->eias[ $i ]['Local Municipality'] = $crawler->filter('#ctl00_Content_lblLocal')->first()->text();
            $this->eias[ $i ]['Application Process'] = $crawler->filter('#ctl00_Content_lblProcess')->first()->text();

            $this->eias[ $i ]['Project Description'] = $crawler->filter('#ctl00_Content_lblProjectDescription')->first()->text();
            $this->eias[ $i ]['Project Title'] = $crawler->filter('#ctl00_Content_lblProjectTitle')->first()->text();
            $this->eias[ $i ]['Property Name'] = $crawler->filter('#ctl00_Content_lblPropertyName')->first()->text();
            $this->eias[ $i ]['SGID'] = $crawler->filter('#ctl00_Content_lblSGID')->first()->text();

            $this->eias[ $i ]['Applicant'] = [];

            $crawler->filter('tr#ctl00_Content_trViewReport table#ctl00_Content_dgApplicant > tr')->each(function ($node, $i_1) use ($i) {

                if ($i_1 != 0 && $node->children()->eq(0)->text() !== '') {
                    $this->eias[ $i ]['Applicant'][ $i_1 - 1 ] = [];

                    $this->eias[ $i ]['Applicant'][ $i_1 - 1 ]['Applicant Type'] = $node->children()->eq(0)->text();
                    $this->eias[ $i ]['Applicant'][ $i_1 - 1 ]['Applicant Name'] = $node->children()->eq(1)->text();
                    $this->eias[ $i ]['Applicant'][ $i_1 - 1 ]['Registration Number'] = $node->children()->eq(2)->text();
                    $this->eias[ $i ]['Applicant'][ $i_1 - 1 ]['Telephone Number'] = $node->children()->eq(3)->text();
                }
            });

            $this->eias[ $i ]['History'] = [];

            $crawler->filter('tr#ctl00_Content_trViewReport table#ctl00_Content_dgHistory > tr')->each(function ($node, $i_1) use ($i) {

                if ($i_1 != 0) {
                    $this->eias[ $i ]['History'][ $i_1 - 1 ] = [];

                    $this->eias[ $i ]['History'][ $i_1 - 1 ]['Stage'] = $node->children()->eq(0)->text();
                    $this->eias[ $i ]['History'][ $i_1 - 1 ]['Assigned Capturer'] = $node->children()->eq(1)->text();
                    $this->eias[ $i ]['History'][ $i_1 - 1 ]['Date System Recorded the Action'] = $node->children()->eq(2)->text();
                    $this->eias[ $i ]['History'][ $i_1 - 1 ]['Stage Case Officer'] = $node->children()->eq(3)->text();
                }

            });

            $this->scrape->data_array = $this->eias;


            \Storage::put(
                $this->scrape->file_directory . '/' . $this->scrape->file_name . '-' . $this->scrape->id . '.json',
                json_encode($this->eias)
            );

        }

        if (\Storage::exists($this->scrape->file_directory . '/' . $this->scrape->file_name . '.json')) {
            \Storage::delete($this->scrape->file_directory . '/' . $this->scrape->file_name . '.json');
        };

        \Storage::copy(
            $this->scrape->file_directory . '/' . $this->scrape->file_name . '-' . $this->scrape->id . '.json',
            $this->scrape->file_directory . '/' . $this->scrape->file_name . '.json'
        );

        \Log::info('SCRAPER [' . $this->scraper->slug . ']: Scrape EIAs completed.');

        return 'Awesome';

    }

}
