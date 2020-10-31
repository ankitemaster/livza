<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Kyslik\ColumnSortable\Sortable;

class Gifts extends Eloquent
{
    use Sortable;
    protected $connection = 'mongodb';
    protected $collection = 'gifts';
    protected $fillable = [
        'gft_title', 'gft_icon', 'gft_gems', 'created_at', 'updated_at',
    ];
    protected $sortable = [
        'gft_title', 'gft_gems', 'created_at', 'updated_at',
    ];
}
