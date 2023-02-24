<?php

namespace App\Console\Commands;

use App\Services\UsaLotteryScraper;
use Illuminate\Console\Command;

class ScrapeUsaLotteries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:usa-lotteries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape all USA lottery names';

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

        $usaLotteryScraper->scrapeLotteries();

        echo "[" . date('d-M-Y H:i:s') . "] Job End\n";
    }
}
