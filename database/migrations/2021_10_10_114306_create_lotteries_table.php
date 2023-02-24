<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotteriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotteries', function (Blueprint $table) {
            $table->id();
            $table->string('country');
            $table->string('state')->nullable(); //only USA/Canada has states, Europe has countries so make this optional
            $table->string('name');
            $table->string('slug');
            $table->tinyInteger('main_balls_count')->default(0);
            $table->tinyInteger('bonus_balls_count')->default(0);
            $table->tinyInteger('url')->nullable();
            //$table->string('slug');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lotteries');
    }
}
