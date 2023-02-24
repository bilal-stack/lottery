<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CountryRequest;
use App\Http\Requests\CountryStateRequest;
use App\Http\Requests\CountryStateSlugRequest;
use App\Http\Requests\LotteryIdRequest;
use App\Http\Requests\LotteryResultsRequest;
use App\Models\Lottery;
use App\Models\LotteryNumber;
use App\Models\UsaState;
use CountryState;
use Illuminate\Http\Request;


class LotteryController extends Controller
{
    public function getStates(CountryRequest $request)
    {
        $country = $request->get('country');

        $lotteryStates = Lottery::with(['usaState'])->where('country', $country)->groupBy('state')->orderBy('state')->get();
        $states = [];
        foreach ($lotteryStates as $lotteryState) {
            $states[$lotteryState->state] = $lotteryState->usaState ? $lotteryState->usaState->name : '';
        }
        $data = [
            'states' => $states,
        ];
        return response()->json($data);
    }

    public function getLotteries(CountryStateRequest $request)
    {
        $country = $request->get('country');
        $state = $request->get('state');

        $data = [
            'state' => UsaState::where('code', $state)->first(),
            'lotteries' => Lottery::with(['resultsLatest', 'usaState'])
                ->where('country', $country)
                ->where('state', $state)->get(),
        ];
        return response()->json($data);
    }

    public function getLottery(CountryStateSlugRequest $request)
    {
        $country = $request->get('country');
        $state = $request->get('state');
        $slug = $request->get('slug');

        $data = [
            'state' => UsaState::where('code', $state)->first(),
            'lottery' => Lottery::with(['resultsLatest', 'usaState'])
                ->where('country', $country)
                ->where('state', $state)
                ->where('slug', $slug)
                ->first(),
        ];
        return response()->json($data);
    }

    public function getLotteryDrawYears(LotteryIdRequest $request)
    {
        $lotteryId = $request->get('id');

        $data = [
            'lottery' => Lottery::with(['usaState'])->find($lotteryId),
            'draw_years' => LotteryNumber::where('lottery_id', $lotteryId)
                ->selectRaw('YEAR(draw_date) AS year')
                ->groupBy('year')
                ->orderBy('year', 'DESC')
                ->pluck('year')
        ];
        return response()->json($data);
    }

    public function getResults(LotteryResultsRequest $request)
    {
        $lotteryId = $request->get('id');
        $year = $request->get('year', date('Y'));

        $sql = LotteryNumber::select('draw_date', 'balls', 'ball_bonus', 'jackpot')
            ->where('lottery_id', $lotteryId)
            ->whereYear('draw_date', $year);

        if ($drawDate = $request->get('draw_date')) {
            $sql->where('draw_date', $drawDate);
        }

        $results = $sql->orderBy('draw_date', 'DESC')->get();

        $data = [
            'lottery' => Lottery::find($lotteryId),
            'results' => $results,
        ];
        return response()->json($data);
    }

    public function show(Request $request, $lottery)
    {
        $lottery = Lottery::with(['results' => function ($query) {

            if (request('year')) {
                $query->whereYear('draw_date', request('year'));
            }

        }])->find($lottery);

        $states = CountryState::getStates('US');
        $all_state_lotteries = Lottery::whereState($lottery->state)->get();

        $data = [
            'lottery' => $lottery,
            'states' => $states,
            'all_state_lotteries' => $all_state_lotteries
        ];

        return response()->json($data);
    }
}
