<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Kyslik\ColumnSortable\Sortable;
class Settings extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'settings';
    protected $fillable = [
        'sitename', 'meta_title', 'logo', 'favicon', 'default_usr_img', 'gem_icon', 'api_settings', 'copyrights', 'contact_emailid', 'social_links', 'locations','calls_debits'
    ];
}
