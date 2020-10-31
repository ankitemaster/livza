<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Streams extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'streams';
    protected $fillable = [
        'active_status', 'live_status', 'thumbnail', 'viewers', 'likes', 'name', 'publisher_id', 'title', 'publisher_age', 'publisher_gender', 'created_at', 'updated_at'
    ];
}
