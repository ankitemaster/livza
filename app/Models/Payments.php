<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Payments extends Eloquent
{
    //
    protected $connection = 'mongodb';
    protected $collection = 'payments';
    protected $fillable = [
        'user_id', 'pymt_type', 'pymt_amt', 'pymt_transid', 'pymt_on',
    ];
}
