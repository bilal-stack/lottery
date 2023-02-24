<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotteryNumber extends Model
{
    protected $table = 'lottery_numbers';

    protected $fillable = [
        'lottery_id', 'draw_date', 'balls', 'ball_bonus', 'jackpot', ];

    protected $casts = [
        'balls' => 'json',
    ];

    public function lottery()
    {
        return $this->belongsTo(Lottery::class, 'lottery_id');
    }

}
