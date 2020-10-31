<?php
use App\Classes\MyClass;
$myClass = new MyClass();
$settings = $myClass->site_settings();
?>
<!DOCTYPE html>
<html lang="en">
   @include('client.layouts.header')
   <body class="">
 <header>
   <nav class="navbar header_bg padding15 fixed-top zindex66 border_bottom" id="custom-nav" role="navigation">
      <div class="container width_header">
       <a href="{{ url('/') }}" class="clr_black">  <span class="home"><img src="{{ URL::asset('public/client_assets/images/icons/home.svg') }}" class="img-fluid align-middle svg"></span>&nbsp;&nbsp;<span class="back_home">Back to home</span></a>
         <div class="navbar-header mx-auto pos_rel">
            <a class="navbar-brand" href="{{ url('/') }}">
       <img src="{{ URL::asset('public/img/logo/'.$siteSettings->darklogo) }}" class="img-fluid header_logo" alt="Logo">
            </a>
         </div>
         <!--/.nav-collapse -->
      </div>
      <!--/.container-fluid -->
   </nav>
</header>
<div class="bg_cover help_cover">
<div class="help_menu">
   <div class="container">
      <div class="row">
         <div class="col-12 col-sm-12 col-md-4 col-lg-3">
            <div class="help_nav text-center" data-toggle="collapse" data-target="#help_menu_mobile"><img src="{{ URL::asset('public/client_assets/images/nav.png') }}" class="img-fluid" /></div>
         <div class="help_headings collapse" id="help_menu_mobile">
            <h2><a href="{{ url('/contact') }}" class="active">Contact Us</a></h2>
            <h2><a href="{{ url('/privacy') }}">Privacy Policy</a></h2>
            <h2><a href="{{ url('/faq') }}">FAQ</a></h2>
            <h2><a href="{{ url('/terms') }}">Terms of Use</a></h2>
         </div>
      </div>
      <div class="col-12 col-sm-12 col-md-8 col-lg-9">
          <div class="help_desc">
            <h1 class="help_title">Contact us</h1>
            {!! Form::open(['route' => 'help.contactmail','method'=>'POST']) !!}
            <div class="row margin_top30">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
               {!! Form::text('mail_firstname', null, ['id'=>"mail_firstname",'class' => 'form-control help_input','placeholder' => trans('First Name')]) !!}
               <p class="float-right red_txt"> @if ($errors->has('mail_firstname'))
                                                           {{ $errors->first('mail_firstname') }}
                                                             @endif</p>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 margin_top20_mobile">
               {!! Form::text('mail_lastname', null, ['id'=>"mail_lastname",'class' => 'form-control help_input','placeholder' => trans('Last Name')]) !!}
               <p class="float-right red_txt"> @if ($errors->has('mail_lastname'))
                                                           {{ $errors->first('mail_lastname') }}
                                                             @endif</p>
            </div>
            </div>
            <div class="row margin_top20">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
               {!! Form::text('mail_id', null, ['id'=>"mail_id",'class' => 'form-control help_input','placeholder' => trans('Email Id')]) !!}
               <p class="float-right red_txt"> @if ($errors->has('mail_id'))
                                                           {{ $errors->first('mail_id') }}
                                                             @endif</p>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 margin_top20_mobile">
               {!! Form::text('mail_phone', null, ['id'=>"mail_phone",'class' => 'form-control help_input','placeholder' => trans('Phone Number')]) !!}
               <p class="float-right red_txt"> @if ($errors->has('mail_phone'))
                                                           {{ $errors->first('mail_phone') }}
                                                             @endif</p>
            </div>
            </div>
            {!! Form::textarea('mail_message', null, ['id'=>"mail_message",'rows'=>'5','class' => 'form-control margin_top20','placeholder' => trans('Message')]) !!}
            <p class="float-right red_txt"> @if ($errors->has('mail_message'))
                                                           {{ $errors->first('mail_message') }}
                                                             @endif</p>
            {!! Form::submit('Send', ['class' => 'btn btn_default primary_clr_bg full_width margin_top20']) !!}
            {!! Form::close() !!}
            <div class="mail id text-center margin_top30"><span class="mail_img align-top"><img src="{{ URL::asset('public/client_assets/images/icons/mail.svg') }}" class="img-fluid svg align-middle"></span> <span class="align-middle">How can help you? <a href="mailto:<?php echo $settings->contact_emailid; ?>" class="primary_clr_txt"><?php echo $settings->contact_emailid; ?></a></span></div>
         </div>
      </div>
      </div>
   </div>
  </div>
</div>
@include('client.layouts.footer')
   </body>
</html>
