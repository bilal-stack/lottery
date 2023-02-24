<?php

namespace App\Console\Commands;

use App\Services\CountrysideScraper;
use App\Services\UsaLotteryScraper;
use Illuminate\Console\Command;

class ScrapeCountryside extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:countryside';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape all Countryside properties';

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
    public function handle(CountrysideScraper $countrysideScraper)
    {
        echo "[" . date('d-M-Y H:i:s') . "] Job Start\n";

        $countrysideScraper->scrape();

        echo "[" . date('d-M-Y H:i:s') . "] Job End\n";
    }
}
