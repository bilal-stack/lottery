<?php

namespace App\Services;

use App\Models\ApiScraperKey;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;

class CountrysideScraper
{

    private $scraperApiUrl;

    public function __construct()
    {
        //Rotate Scraper API KEYs - we may need to have about 15 keys, so we can scrape every day for 1 month
//        $key = ApiScraperKey::orderBy('last_used_at', 'ASC')->first();
//        $key->last_used_at = Carbon::now();
//        $key->save();
//
//        $this->scraperApiUrl = sprintf('http://api.scraperapi.com?api_key=%s&url=', $key['key']);
    }

    public function scrape()
    {
        ini_set('max_execution_time', 180); //3 minutes

        $client = new \GuzzleHttp\Client();

        // starts at: 281
        for ($id = 500; $id <= 999; $id++) {

            $url = "https://www.beaulieu.uk.com/plot/" . $id;

            echo "[" . date('d-M-Y H:i:s') . "] URL: " . $url . "\n";

            try {
                $result = $client->request('GET', $url, []);
                $page = $result->getBody()->getContents();

                $crawler = new Crawler($page);
                $record = $crawler->filter('h1');
                $h1 = $record->text();
                dump($h1);

                if (stripos($h1, 'Chelmer') !== false || stripos($h1, 'Turnstone') !== false || stripos($h1, 'Rosefinch') !== false) {
                    //if(stripos($h1, 'Chelmer') !== false) {
                    exit;
                }
            } catch (\Exception $e) {
                //dump($e->getMessage());
            }

        }
    }


}
