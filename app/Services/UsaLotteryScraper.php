<?php

namespace App\Services;

use App\Models\ApiScraperKey;
use App\Models\Lottery;
use App\Models\LotteryNumber;
use App\Models\UsaState;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use DB;

class UsaLotteryScraper
{

    private $scraperApiUrl;

    private $recordExists;

    private $retryCount = 0;

    public function __construct()
    {
        //Rotate Scraper API KEYs - we may need to have about 15 keys, so we can scrape every day for 1 month
        $key = ApiScraperKey::orderBy('last_used_at', 'ASC')->first();
        $key->last_used_at = Carbon::now();
        $key->save();

        $this->scraperApiUrl = sprintf('http://api.scraperapi.com?api_key=%s&url=', $key['key']);
        //$this->scraperApiUrl = sprintf('http://api.scraperapi.com?api_key=%s&url=', config('scraper.key'));
        //$this->scraperApiUrl = sprintf('http://127.0.0.1:5000/?url=');
    }

    public function scrapeLotteries()
    {
        ini_set('max_execution_time', 180); //3 minutes

        // $crawler = Goutte::request('GET', "https://lottery.com/us-lotteries/");

        $states = UsaState::all();
        $counter = 0;

        foreach ($states as $key => $state) {

            $crawler = $this->scrapeRequest("https://lotterypost.com/results/" . $state->code);
            //$crawler = $this->scrapeRequest("https://lotterypost.com/results/al");

            $notFound = $crawler->filter('#content')->each(function ($content) {
                $content->filter('h1 > .main')->each(function ($content) {
                    if ($content->text() == 'Page not found') {
                        return true;
                    }
                    return false;
                });
            });

            if (!$notFound) {
                break;
            }

            $stateCode = $state->code;

            $crawler->filter('.resultsgame')->each(function ($node) use (&$counter, &$stateCode) {
                $node->filter('.resultsdrawing')->each(function ($content) use (&$counter, &$stateCode) {

                    $name = '';
                    if ($content->filter('h2')->count() > 0) $name = $content->filter('h2')->text();

                    $drawDate = '';
                    if ($content->filter('time')->count() > 0) $drawDate = $content->filter('time')->attr('datetime');

                    //$drawDate = $content->filter('.resultsnextdate')->text();

                    $jackpot = '';
                    if ($content->filter('.resultsJackpotAmount > span > span')->count() > 0) $jackpot = $content->filter('.resultsJackpotAmount > span > span')->text();

                    $balls = [];
                    $bonusBalls = '';
                    $bonusBallsName = '';

                    $content->filter('.resultsnumbers')->each(function ($node) use (&$balls, &$bonusBalls, &$bonusBallsName) {
                        $node->filter('.resultsnumsrow')->each(function ($nextRow) use (&$balls, &$bonusBalls, &$bonusBallsName) {

                            $nextRow->first()->filter('ul > li')->each(function ($next) use (&$balls) {
                                return $balls[] = $next->text();
                            });

                            $nextRow->nextAll()->filter('ul > li')->each(function ($next) use (&$nextRow, &$bonusBalls, &$bonusBallsName) {
                                $bonusBallsName = $nextRow->nextAll()->text();
                                $bonusBallsName = substr($bonusBallsName, 0, strpos($bonusBallsName, ":"));
                                return $bonusBalls = $next->text();
                            });
                        });
                    });

                    $slug = Str::slug($name . $stateCode);
                    $drawDate = Carbon::parse($drawDate)->format('Y-m-d');
                    $jackpot = str_replace('$', '', $jackpot);
                    $jackpot = str_replace(',', '', $jackpot);

                    if ('' !== $bonusBalls) {
                        if (!empty($balls)) {
                            array_pop($balls);
                        }
                    }

                    $counter++;

                    $lottery = Lottery::firstOrCreate(
                        [
                            'country' => 'us',
                            'name' => trim($name),
                            'state' => $stateCode,
                            'bonus_balls_to_pick' => $bonusBalls,
                            'bonus_balls_name' => $bonusBallsName
                        ],
                        [
                            'slug' => $slug
                        ]
                    );

                    $lottery->results()->create([
                        'draw_date' => $drawDate,
                        'balls' => json_encode($balls),
                        'ball_bonus' => $bonusBalls,
                        'jackpot' => (int)$jackpot
                    ]);

                    echo "[" . date('d-M-Y H:i:s') . "] " . sprintf('[%d] Country: %s State: %s Lottery: %s [%s]', $counter, $lottery->country, $lottery->state, $lottery->name, $lottery->slug) . "\n";
                });
            });
        }

        return $counter;
    }

    public function scrapeLotteriesOld()
    {
        ini_set('max_execution_time', 180); //3 minutes

        // $crawler = Goutte::request('GET', "https://lottery.com/us-lotteries/");

        $crawler = $this->scrapeRequest("https://lottery.com/us-lotteries");

        $record = $crawler->filter('resultsgrid');

        $counter = 0;
        $record->each(function ($node, $i) use (&$counter) {

            $counter++;

            $url = $node->filter('a')->attr('href');

            $uri_segments = explode('/', $url);

            $lottery = Lottery::firstOrCreate(
                [
                    'country' => 'us',
                    'state' => $uri_segments[2],
                    'slug' => $uri_segments[3],
                ],
                [
                    'name' => trim($node->text()),
                ]
            );

            $chk = $this->checkIfBonusBallAvailable($lottery);

            $lottery->bonus_balls_to_pick = $chk;
            $lottery->save();

            echo "[" . date('d-M-Y H:i:s') . "] " . sprintf('[%d] Country: %s State: %s Lottery: %s [%s]', $counter, $lottery->country, $lottery->state, $lottery->name, $lottery->slug) . "\n";
        });

        return $counter;
    }

    public function checkIfBonusBallAvailable($lottery)
    {
        /*
        to check if bonus ball is available for this state and lottery,
        we will scrap below page
        https://lottery.com/numbers/az/powerball/
        if record exist in the table at second row the last ball is highlighted then
        the lottery has bonus ball and it will always be the last one
        */

        $url = "https://lottery.com/numbers/{$lottery->state}/{$lottery->slug}";

        $crawler = $this->scrapeRequest($url);

        $result = $crawler->filter('.lottery-ball');

        $counter = 0;

        $result->each(function ($node, $i) use (&$counter) {
            if (strpos($node->attr('class'), 'bonus') > -1) {
                $counter = 1;
            }
        });

        return (bool)$counter;

        return count($result) ? true : false;

    }

    public function formatBonusBall($string, $isBonusBall = false)
    {
        $explode = explode("-", $string);

        $end = end($explode);
        $chk = strpos($end, ":");

        if ($chk > -1) {
            //unset last element
            $length = count($explode);
            unset($explode[$length - 1]);
        } else {
        }

        if ($isBonusBall) {
            $bonus_ball = end($explode);
            array_pop($explode);

            return [
                'balls' => $explode,
                'ball_bonus' => $bonus_ball,
            ];

        } else {
            return [
                'balls' => $explode,
                'ball_bonus' => null,
            ];
        }
    }

    public function scrapeLotteryNumbers($state = null, $lotterySlug = null, $reScrapeEverything = false)
    {
        //for all the states and lotteries
        $query = Lottery::where('country', 'us');

        if ($state) {
            $query->where('state', $state);
        }

        if ($lotterySlug) {
            $query->where('slug', $lotterySlug);
        }

        $lotteries = $query->get();

        foreach ($lotteries as $key => $lottery) {

            //reset retry counter
            $this->retryCount = 0;

            $this->recordExists = false;

            echo "[" . date('d-M-Y H:i:s') . "] Lottery: {$lottery->name} \n";

            $year = date('Y');

            do {
                //First check if we have a bonus ball
                $isBonusBallAvailable = $lottery->bonus_balls_to_pick;

                $url = "https://lottery.com/previous-results/{$lottery->state}/{$lottery->slug}/{$year}";

                echo "[" . date('d-M-Y H:i:s') . "] URL: {$url}\n";

                // sleep(10);
                sleep(3);

                try {
                    $crawler = $this->scrapeRequest($url);

                    //check if 404 / results not available / Defender Block
                    if ($this->checkFor404Page($crawler) || $this->checkForNoResultsAvailable($crawler)) {
                        echo "[" . date('d-M-Y H:i:s') . "] No results available for year: {$year}\n";
                        break;
                    }

                    $counter = 0;

                    $crawler->filter('.lottery-table tbody > tr ')->each(function ($node, $i) use ($key, $year, $lottery, &$counter, $isBonusBallAvailable, $reScrapeEverything) {

                        $counter++;

                        $lotteryArray = [];

                        $node->children()->each(function ($node, $index) use (&$lotteryArray, $key) {
                            array_push($lotteryArray, $node->text(''));
                        });

                        $draw_date = Carbon::parse($lotteryArray[1])->format('Y-m-d');

                        //check if record exists
                        if ($reScrapeEverything === false) {
                            $recordExists = LotteryNumber::where('lottery_id', $lottery->id)->where('draw_date', $draw_date)->get();
                            if (count($recordExists)) {
                                echo "[" . date('d-M-Y H:i:s') . "] Record exist for: {$lottery->name} Draw Date: {$draw_date} \n";
                                $this->recordExists = true;
                                return false;
                            }
                        }

                        $result = $this->formatBonusBall($lotteryArray[3], $isBonusBallAvailable);

                        if ($result) {

                            if (!count($result['balls'])) {
                                echo "[" . date('d-M-Y H:i:s') . "] Balls empty!\n";
                                dump($result);
                            }

                            $jackpot = preg_replace('/[^0-9]/', '', $lotteryArray[2]);
                            if (!$jackpot) {
                                $jackpot = null;
                            }

                            LotteryNumber::updateOrCreate(
                                [
                                    'lottery_id' => $lottery->id,
                                    'draw_date' => $draw_date
                                ],
                                [
                                    'balls' => $result['balls'],
                                    'ball_bonus' => $result['ball_bonus'],
                                    'jackpot' => $jackpot,
                                ]
                            );
                        }
                    });

                    if ($this->recordExists === true) {
                        break;
                    }

                    echo "[" . date('d-M-Y H:i:s') . "] Scraped records: {$counter}\n";

                } catch (Exception $e) {

                    echo "[" . date('d-M-Y H:i:s') . "] Exception: {$e->getMessage()}\n";
                    //dump($crawler->text());
                    Log::info(["$url throws error and it is not scrapped", $e->getMessage()]);
                    $this->retryCount++;

                    if ($this->retryCount <= 2) {
                        //retry the same year
                        $year++;
                    } else {
                        echo "[" . date('d-M-Y H:i:s') . "] Retry Count exceeded: {$this->retryCount}\n";
                    }

                }

                //go back to previous year
                $year--;

            } while (true);

            /**
             * AUTO-POPULATE multi-state lotteries
             * If lottery is multi-state, update all same multi-state results, so we don't need to scrape it again for different states
             *
             * CHECK:
             *
             * select l.id, state, name, count(*) from lotteries l
             * inner join lottery_numbers ln on ln.lottery_id = l.id
             * where name="mega millions"
             * group by l.id, state, name;
             */
            if ($lottery->is_multi_state == true) {
                $multiStateLotteries = Lottery::where('country', 'us')->where('slug', $lottery->slug)->get();
                foreach ($multiStateLotteries as $multiStateLottery) {
                    DB::statement(sprintf('INSERT IGNORE INTO lottery_numbers SELECT null, %d, draw_date, balls, ball_bonus, jackpot, created_at, updated_at FROM lottery_numbers WHERE lottery_id=%d', $multiStateLottery->id, $lottery->id));
                }
            }

        }
    }

    private function checkFor404Page($crawler)
    {
        return $crawler->filter('.pagenotfound-title')->getNode(0);
    }


    private function checkForNoResultsAvailable($crawler)
    {

        $text = $crawler->filter('table.lottery-table td')->text();

        if (strpos($text, 'No Results') > -1) {
            return true;
        }

        return false;

    }

    public function scrapeRequest($url, $proxy = false)
    {
        $final = $this->scraperApiUrl . $url;

        $ch = curl_init($final);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);

        $curl_scraped_page = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);

        $crawler = new Crawler($curl_scraped_page);

        $text = $crawler->filter('body')->text();
        if (stripos($text, 'request limit') !== false) {
            exit('[scraperapi.com] Request Limit Reached for KEY: ' . config('scraper.key'));
        }
        return $crawler;
    }

    private function getStates()
    {
        return [
            'AL' => "Alabama",
            'AK' => "Alaska",
            'AZ' => "Arizona",
            'AR' => "Arkansas",
            'CA' => "California",
            'CO' => "Colorado",
            'CT' => "Connecticut",
            'DE' => "Delaware",
            'DC' => "District Of Columbia",
            'FL' => "Florida",
            'GA' => "Georgia",
            'HI' => "Hawaii",
            'ID' => "Idaho",
            'IL' => "Illinois",
            'IN' => "Indiana",
            'IA' => "Iowa",
            'KS' => "Kansas",
            'KY' => "Kentucky",
            'LA' => "Louisiana",
            'ME' => "Maine",
            'MD' => "Maryland",
            'MA' => "Massachusetts",
            'MI' => "Michigan",
            'MN' => "Minnesota",
            'MS' => "Mississippi",
            'MO' => "Missouri",
            'MT' => "Montana",
            'NE' => "Nebraska",
            'NV' => "Nevada",
            'NH' => "New Hampshire",
            'NJ' => "New Jersey",
            'NM' => "New Mexico",
            'NY' => "New York",
            'NC' => "North Carolina",
            'ND' => "North Dakota",
            'OH' => "Ohio",
            'OK' => "Oklahoma",
            'OR' => "Oregon",
            'PA' => "Pennsylvania",
            'RI' => "Rhode Island",
            'SC' => "South Carolina",
            'SD' => "South Dakota",
            'TN' => "Tennessee",
            'TX' => "Texas",
            'UT' => "Utah",
            'VT' => "Vermont",
            'VA' => "Virginia",
            'WA' => "Washington",
            'WV' => "West Virginia",
            'WI' => "Wisconsin",
            'WY' => "Wyoming"
        ];
    }
}
