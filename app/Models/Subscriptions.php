<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Kyslik\ColumnSortable\Sortable;

class Subscriptions extends Eloquent
{
    use Sortable;
    protected $connection = 'mongodb';
    protected $collection = 'subscriptions';
    protected $fillable = [
        'subs_title', 'subs_gems', 'subs_price', 'subs_validity', 'platform', 'updated_at', 'created_at',
    ];
    public $sortable = [
        'subs_title', 'subs_gems', 'subs_price', 'subs_validity', 'updated_at', 'created_at',
    ];
}
