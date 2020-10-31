<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Kyslik\ColumnSortable\Sortable;

class Hashtags extends Eloquent
{
    use Sortable;
    protected $connection = 'mongodb';
    protected $collection = 'hashtags';
    protected $fillable = [
        'topic','created_at', 'updated_at',
    ];
    protected $sortable = [
        'topic','created_at', 'updated_at',
    ];
}
