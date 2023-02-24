<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LotteryController;
use App\Http\Controllers\ScraperController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Symfony\Component\DomCrawler\Crawler;

Route::get('/', function () { return redirect('home'); });

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

//Route::resource('lottery', LotteryController::class);

Route::prefix('lottery')->group(function () {
    Route::get('/', [LotteryController::class, 'index'])->name('lottery.index');
    Route::get('/state/{state}', [LotteryController::class, 'state'])->name('lottery.state');
    Route::get('/show/{lottery}', [LotteryController::class, 'show'])->name('lottery.show');
});

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::prefix('scraper')->group(function () {
        Route::get('/', [ScraperController::class, 'index'])->name('scraper');
        Route::get('/scrape-usa-lotteries', [ScraperController::class, 'scrapeUsaLotteries'])->name('scraper.scrape.usa.lotteries');
        Route::get('/scrape-usa-lottery-numbers', [ScraperController::class, 'scrapeUsaLotteryNumbers'])->name('scraper.scrape.usa.lottery.numbers');
    });

});
