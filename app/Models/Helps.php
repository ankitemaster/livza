<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Kyslik\ColumnSortable\Sortable;
class Helps extends Eloquent
{
	use Sortable;
	protected $connection = 'mongodb';
    protected $collection = 'helps';
    protected $fillable = [
        'help_title', 'help_descrip',
    ];
    public $sortable = ['help_title'];
}
