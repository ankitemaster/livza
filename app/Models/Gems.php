<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Gems extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'gems';
    protected $fillable = [
        'gem_title', 'gem_count', 'gem_price', 'gem_icon','platform','created_at', 'updated_at',
    ];
}
