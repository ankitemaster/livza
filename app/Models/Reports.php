<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Reports extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'reports';
    protected $fillable = [
        'user_id', 'rept_by', 'rept_detail', 'rept_on', 'updated_at'
    ];
}
