<?php

namespace App\Services;

use App\Models\Lottery;
use App\Models\LotteryNumber;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LotteryService
{

    public function copyResultsForMultiStateLotteries()
    {
        //get all multi-state lotteries slugs
        $multiStateLotteries = Lottery::where('is_multi_state', true)->groupBy('slug')->pluck('slug');

        //copy results
        foreach ($multiStateLotteries as $lotterySlug) {

            echo "[" . date('d-M-Y H:i:s') . "] SLUG: {$lotterySlug}\n";

            //get lottery source with the most results
            $lotterySource = $this->findLotteryWithMostLotteryResults($lotterySlug);

            //get all results and copy everywhere
            foreach (LotteryNumber::where('lottery_id', $lotterySource->id)->get() as $lotteryNumbers) {
                foreach(Lottery::where('slug',$lotterySlug)->get() as $lottery) {
                    LotteryNumber::firstOrCreate(
                        [
                            'lottery_id' => $lottery->id,
                            'draw_date' => $lotteryNumbers->draw_date
                        ],
                        [
                            'balls' => $lotteryNumbers->balls,
                            'ball_bonus' => $lotteryNumbers->ball_bonus,
                            'jackpot' => $lotteryNumbers->jackpot,
                        ]
                    );
                }
            }
        }
    }

    public function findLotteryWithMostLotteryResults($slug)
    {
        //DB::enableQueryLog(); // Enable query log

        $result = LotteryNumber::selectRaw('lotteries.*, COUNT(*) AS total')
            ->join('lotteries', 'lotteries.id', '=', 'lottery_numbers.lottery_id')
            ->where('slug', $slug)
            ->groupBy('lotteries.id')
            ->orderBy('total', 'DESC')
            ->first();

        return $result;

        //dd(DB::getQueryLog()); // Show results of log
    }


}
