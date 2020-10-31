<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Videochats extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'videochats';
    protected $fillable = [
        'user_id', 'partner_id', 'lastchat_on',
    ];
}
