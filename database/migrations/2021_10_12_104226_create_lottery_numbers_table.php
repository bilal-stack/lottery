<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotteryNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lottery_numbers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lottery_id');
            $table->date('draw_date');
            $table->json('balls')->nullable();
            $table->integer('ball_bonus')->nullable();
            $table->integer('jackpot')->nullable();
            $table->timestamps();
        });

        Schema::table('lottery_numbers', function (Blueprint $table) {
            $table->foreign('lottery_id', 'fk_lottery_numbers_lotteries')->references('id')->on('lotteries')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        Schema::table('lottery_numbers', function (Blueprint $table) {
            $table->unique(['lottery_id', 'draw_date'], 'lottery_id_draw_date_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lottery_numbers');
    }
}
