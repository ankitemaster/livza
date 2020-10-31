<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class Messages extends Eloquent
{
    
    protected $connection = 'mongodb';
    protected $collection = 'messages';
    protected $fillable = [
        'msg_info', 'msg_of', 'block_status', 'msg_chat', 'msg_type', 'msg_data', 'msg_at'
    ];
}
