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
    <title>{{$settings->sitename}} | {{$settings->meta_title}}</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('/img/logo/'.$settings->favicon)}}">
    <!-- Custom Stylesheet -->
    <link href="{{ URL::asset('public/admin_assets/main/css/style.css') }}" rel="stylesheet">
    
</head>

<body class="h-100">
 <div class="h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-md-5">
                    <div class="form-input-content text-center">
                        <div class="mb-5">
                            <a class="btn btn-primary rounded-0" href="{{route('admin.dashboard')}}">Back</a>
                        </div>
                        <h1 class="error-text text-white font-weight-bold">404</h1>
                            <h4 class="mt-4"><i class="fa fa-exclamation-triangle text-warning"></i>{{trans('app.The page you were looking for is not found!')}} </h4>
                            <p>{{trans('app.You may have mistyped the address or the page may have moved.')}}</p>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ URL::asset('public/admin_assets/assets/plugins/common/common.min.js') }}"></script>
    <!-- Custom script -->
    <script src="{{ URL::asset('public/admin_assets/main/js/custom.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin_assets/main/js/settings.js') }}"></script>
    <script src="{{ URL::asset('public/admin_assets/main/js/quixnav.js')}}"></script>
   </body>
<style type="text/css">
        .toast{
            display: none!important;
        }
    </style>

</html>