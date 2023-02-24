<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableLotteriesAddMoreInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lotteries', function (Blueprint $table) {
            $table->string('logo_url')->nullable()->after('url');
            $table->integer('jackpot_odds')->nullable()->after('logo_url');
            $table->integer('main_balls_to_pick')->nullable()->after('slug');
            $table->integer('bonus_balls_to_pick')->nullable()->after('main_balls_count');
            $table->string('bonus_balls_name')->nullable()->after('bonus_balls_count');
            $table->string('bonus_balls_name_short')->nullable()->after('bonus_balls_name');
            $table->json('draw_days')->nullable()->after('bonus_balls_name_short');
            $table->decimal('ticket_price', 4, 2)->nullable()->after('jackpot_odds');
            $table->text('info')->nullable()->after('ticket_price');
        });

        DB::statement("UPDATE lotteries SET main_balls_to_pick = main_balls_count, bonus_balls_to_pick = bonus_balls_count");
        DB::statement("UPDATE lotteries SET main_balls_count = null, bonus_balls_count = null");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
