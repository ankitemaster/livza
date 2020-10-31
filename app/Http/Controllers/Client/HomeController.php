<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Settings;

class HomeController extends Controller
{

    public function index()
    {
        $siteSettings = Settings::first();
        $social = json_decode($siteSettings->social_links);
        return view('client.home', compact('siteSettings', 'social'));
    }
}
