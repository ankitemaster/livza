<?php

namespace App\Models;

use App\Models\Accounts;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Followings extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'followings';
    protected $fillable = [
        'user_id', 'follower_id', 'followed_at'
    ];

    public function accounts()
    {
        return $this->belongsTo(Accounts::class);
    }
}
