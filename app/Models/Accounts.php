<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Kyslik\ColumnSortable\Sortable;



class Accounts extends Eloquent
{
    use Sortable;
    
    protected $connection = 'mongodb';
    protected $collection = 'accounts';
    protected $primaryKey = "_id";
    protected $fillable = [
        'acct_name', 'acct_age', 'acct_birthday', 'acct_email', 'acct_password', 'acct_gender', 'acct_phoneno', 'acct_facebookid', 'acct_sync', 'acct_photo', 'acct_payment_id', 'acct_createdat', 'acct_status','acct_appleid', 'acct_membership','acct_mailid', 'acct_location',
    ];
    public $sortable = ['acct_name', 'acct_age', 'acct_gender', 'created_at'];

    public function followings()
    {
        return $this->hasMany('App\Models\Followings', '_id', 'user_id');
    }
}
