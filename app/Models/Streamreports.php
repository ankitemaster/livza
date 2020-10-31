<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Streamreports extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'streamreports';
    protected $fillable = [
        'user_id', 'rept_by', 'rept_detail', 'rept_on', 'updated_at'
    ];
}
