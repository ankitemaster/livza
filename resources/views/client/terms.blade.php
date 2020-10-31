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
            <h2><a href="{{ url('/contact') }}">Contact Us</a></h2>
            <h2><a href="{{ url('/privacy') }}">Privacy Policy</a></h2>
            <h2><a href="{{ url('/faq') }}">FAQ</a></h2>
            <h2><a href="{{ url('/terms') }}" class="active">Terms of Use</a></h2>
         </div>
      </div>
      <div class="col-12 col-sm-12 col-md-8 col-lg-9">
                   <div class="help_desc">

                   @foreach($helpTerms as $term)
                   <h1 class="help_title">@php echo $term->help_title; @endphp</h1>
                   <p class="margin_top20">@php
                            echo $term->help_descrip;
                        @endphp</p>
                    @endforeach
                     
                    
                     
                  </div>
               </div>
               </div>
            </div>
		    
		   </div> 
         </div>  
@include('client.layouts.footer')
   </body>
</html>

