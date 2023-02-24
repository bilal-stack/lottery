<?php

namespace App\Http\Controllers;

use App\Models\Lottery;
use App\Services\UsaLotteryScraper;
use Illuminate\Http\Request;

class ScraperController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('/scraper/index', []);
    }

    public function scrapeUsaLotteries(Request $request, UsaLotteryScraper $usaLotteryScraper)
    {
        $count = $usaLotteryScraper->scrapeLotteries();
        return redirect()->route('scraper')->with('success', 'All USA Lotteries scraped. Count: ' . $count);
    }

    public function scrapeUsaLotteryNumbers(Request $request, UsaLotteryScraper $usaLotteryScraper)
    {
        $count = $usaLotteryScraper->scrapeLotteryNumbers();
        return redirect()->route('scraper')->with('success', 'All USA Lotteries numbers scraped.');
    }
}
