<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lottery extends Model
{
    use HasFactory;

    protected $table = 'lotteries';

    protected $fillable = [
        'country',
        'state',
        'name',
        'slug',
        'main_balls_to_pick',
        'main_balls_count',
        'bonus_balls_to_pick',
        'bonus_balls_count',
        'bonus_balls_name',
        'bonus_balls_name_short',
        'url',
        'logo_url',
        'is_multi_state',
        'draw_days',
        'info'
    ];

    protected $appends = ['image'];

    protected $casts = [
        'draw_days' => 'array'
    ];

    public function getImageAttribute()
    {
        return "/images/lottery-balls.jpeg";
    }

    public function results()
    {
        return $this->hasMany(LotteryNumber::class, 'lottery_id');
    }

    public function resultsLatest()
    {
        return $this->hasOne(LotteryNumber::class, 'lottery_id')->latest();
    }

    public function usaState()
    {
        return $this->hasOne(UsaState::class, 'code', 'state');
    }
}
