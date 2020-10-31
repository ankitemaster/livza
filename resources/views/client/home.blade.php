 @extends('client.layouts.home')
 @section('content')
 <div class="bg_cover">
         <div class="app_details">
            <div class="container">
               <div class="row">
                  <div class="col-12 col-sm-12 col-md-6 col-lg-5 text-right phone_video">
               <div class="phone">
                <!--<img src="images/video_img.png" alt="video_img" class="img-fluid"/>-->
                <video src="{{ URL::asset('public/client_assets/images/video.webm') }}" autoplay="" loop="" poster="" muted="muted">your browser does not support the video tag</video>
               </div>
               </div>
               <div class="col-12 col-sm-12 col-md-6 col-lg-7">
                  <div class="app_desc margin_top50">
                     <div class="logo">
                       <h1> <img src="{{ URL::asset('public/img/logo/'.$siteSettings->logo) }}" alt="Livza" class="img-fluid livza_logo" /></h1>
                     </div>
                     <h1 class="desc margin_top20">@php echo $siteSettings->page_title; @endphp 
                     </h1>
                     <div class="download_apps margin_top30">
                        <a href="<?php if(empty($social->ioslink)){ echo 'javascript:void(0)';}else{ echo $social->ioslink;} ?>" target="_blank">
                        <div class="ios_app"><button class="btn btn_download"><i class="fa fa-apple font_size25 margin_right10 align-middle"></i>IOS</button></div></a>
                        <a href="<?php if(empty($social->androidlink)){ echo 'javascript:void(0)';}else{ echo $social->androidlink;} ?>" target="_blank">
                        <div class="android_app margin_top20"><button class="btn btn_download"><i class="fa fa-android font_size25 margin_right10 align-middle"></i>Android</button></div></a>
                     </div>
                  </div>
               </div>
               </div>
            </div>
		    
		   </div> 
         </div>  
@endsection