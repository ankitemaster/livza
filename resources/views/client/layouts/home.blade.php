<?php
use App\Classes\MyClass;
$myClass = new MyClass();
$settings = $myClass->site_settings();
?>
<!DOCTYPE html>
<html lang="en">
   @include('client.layouts.header')
   <body class="">
        @yield('content')  
        @include('client.layouts.footer')
   </body>
</html>

