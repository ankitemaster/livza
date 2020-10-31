
<head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
      <meta name="theme-color" content="#05ac90" />
	  <meta name="msapplication-navbutton-color" content="#05ac90">
	  <meta name="apple-mobile-web-app-status-bar-style" content="#05ac90">
	  <meta name="format-detection" content="telephone=no"><!--removes phone number styling in IE edge-->
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('public/img/logo/'.$settings->favicon) }}">
      <title>@php echo $siteSettings->sitename.' | '.$siteSettings->meta_title; @endphp</title>
      <!-- Bootstrap -->
      <link rel="stylesheet" href="{{ URL::asset('public/client_assets/css/bootstrap.min.css') }}">
      <!--Font awesome style-->
      <link rel="stylesheet" href="{{ URL::asset('public/client_assets/css/font-awesome.css') }}">
      <!-- Custom style -->
      <link rel="stylesheet" href="{{ URL::asset('public/client_assets/css/core.css') }}">
      <?php $lang = App::getLocale();
      if($lang == 'ar'){
      ?>
      <link rel="stylesheet" href="{{ URL::asset('public/client_assets/css/common-rtl.css') }}">
      <?php } else {?>
      <link rel="stylesheet" href="{{ URL::asset('public/client_assets/css/common.css') }}">
      <?php } ?>
      <!--E O Font awesome style-->
      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
      @php echo $siteSettings->google_analytics; @endphp
   </head>