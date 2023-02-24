<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiScraperKey extends Model
{

    protected $table = 'api_scraper_keys';

    protected $fillable = [
        'login_email', 'key', 'last_used_at'
    ];

}
