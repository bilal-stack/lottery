<?php

namespace App\Console\Commands;

use App\Services\UsaLotteryScraper;
use Illuminate\Console\Command;

class ScrapeUsaLotteryNumbersByStateAndLottery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:usa-lottery-numbers-by-state-and-lottery {state} {lottery}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape all USA lottery numbers by state and lottery';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(UsaLotteryScraper $usaLotteryScraper)
    {
        echo "[" . date('d-M-Y H:i:s') . "] Job Start\n";

        $usaLotteryScraper->scrapeLotteryNumbers($this->argument('state'), $this->argument('lottery'), true);

        echo "[" . date('d-M-Y H:i:s') . "] Job End\n";
    }
}
