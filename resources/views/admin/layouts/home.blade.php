<?php
use App\Classes\MyClass;
$myClass = new MyClass();
$settings = $myClass->site_settings();
?>
<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{$settings->sitename . " Admin" }} - {{$settings->meta_title}}</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('public/img/logo/'.$settings->favicon) }}">
    <!-- Custom Stylesheet -->
    <link href="{{ URL::asset('public/admin_assets/main/css/style.css') }}" rel="stylesheet">
</head>
<body class="h-100">
    <div id="preloader">
        <div class="spinner">
            <div class="spinner-a"></div>
            <div class="spinner-b"></div>
        </div>
    </div>
    <div class="login-bg h-100">
        <div class="container h-100">
            @yield('content')
        </div>
    </div>
    <!-- #/ container -->
    <!-- Common JS -->
    <script src="{{ URL::asset('public/admin_assets/assets/plugins/common/common.min.js') }}"></script>
    <!-- Custom script -->
    <script src="{{ URL::asset('public/admin_assets/main/js/custom.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin_assets/main/js/settings.js') }}"></script>
    <script src="{{ URL::asset('public/admin_assets/main/js/quixnav.js')}}"></script>
</body>
</html> 