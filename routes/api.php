<?php

use App\Http\Controllers\API\LotteryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['sessions']], function () {
    Auth::routes();
});


Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });

    Route::get('/lottery/states', [LotteryController::class, 'getStates']);
    Route::get('/lottery/lotteries', [LotteryController::class, 'getLotteries']);
    Route::get('/lottery/lottery', [LotteryController::class, 'getLottery']);
    Route::get('/lottery/draw-years', [LotteryController::class, 'getLotteryDrawYears']);
    Route::get('/lottery/results', [LotteryController::class, 'getResults']);

    Route::apiResource('lottery', LotteryController::class);

});

