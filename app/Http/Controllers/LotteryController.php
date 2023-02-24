<?php

namespace App\Http\Controllers;

use App\Models\Lottery;
use App\Models\LotteryNumber;
use App\Models\UsaState;
use Carbon\Carbon;
use CountryState;
use Illuminate\Http\Request;

class LotteryController extends Controller
{
    public function index(Request $request)
    {
        $states = CountryState::getStates('US');
        $lotteries = Lottery::all()->groupBy('state');
        return view('lottery.index', compact('lotteries', 'states'));
    }

    public function state($state, Request $request)
    {
        $state = UsaState::where('code', $state)->first();
        $lotteries = Lottery::where('state', $state->code)->get();
        return view('lottery.state', compact('state', 'lotteries'));
    }

    public function show(Lottery $lottery, Request $request)
    {
        $year = $request->get('year', date('Y'));

        //determine year dropdown
        $yearFrom = $yearTo = date('Y');
        if ($oldestDrawDate = LotteryNumber::where('lottery_id', $lottery->id)->min('draw_date')) {
            $yearFrom = Carbon::parse($oldestDrawDate)->format('Y');
        }
        $yearsHistory = range($yearTo, $yearFrom);

        $results = $lottery->results()->whereYear('draw_date', $year)->orderBy('draw_date', 'DESC')->get();

        $states = CountryState::getStates('US');
        $all_state_lotteries = Lottery::whereState($lottery->state)->get();

        return view('lottery.show', compact('lottery', 'results', 'states', 'all_state_lotteries', 'yearsHistory'));
    }
}
