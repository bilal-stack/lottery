<?php

namespace App\Console\Commands;

use App\Services\LotteryService;
use Illuminate\Console\Command;

class PopulateMultiStateLotteries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lottery:populate-multi-state-lotteries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finds multi state lottery with most results and populates to other state lotteries for same lottery';

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
    public function handle(LotteryService $lotteryService)
    {
        echo "[" . date('d-M-Y H:i:s') . "] Job Start\n";

        $lotteryService->copyResultsForMultiStateLotteries();

        echo "[" . date('d-M-Y H:i:s') . "] Job End\n";
    }
}
