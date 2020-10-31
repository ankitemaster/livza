<footer class="container-fluid footer_section footer_section_mble" id="footer">
 <div class="container">
  <div class="row">
   <div class="col-12 col-sm-12 col-md-4 col-lg-4 mobile_copy">
    <h5>{{trans('app.Language:')}}</h5>
    <?php $lang = App::getLocale();?>
    <select class="lang selectpicker form-control-sm" id="language" onChange="setlang()" data-width="auto">
      <option value="en" <?php echo $lang == 'en' ? 'selected' : ''; ?>>English</option>
      <option value="fr" <?php echo $lang == 'fr' ? 'selected' : ''; ?>>French</option>
      <option value="ar" <?php echo $lang == 'ar' ? 'selected' : ''; ?>>Arabic</option>
    </select>
    <div class="copyrights">
      <br> <p>@php echo $siteSettings->copyrights; @endphp</p>
    </div>
  </div>
  <div class="col-12 col-sm-12 col-md-4 col-lg-4 help_details">
    <h2 class="margin_bottom20">{{trans('app.Help')}}</h2>
    <ul>
     <li><a href="{{ url('/contact') }}">{{ __(trans('app.Contact Us'))}}</a></li>
     <li><a href="{{ url('/privacy') }}">{{ __(trans('app.Privacy Policy'))}}</a></li>
     <li><a href="{{ url('/faq') }}">{{ __(trans('app.FAQ'))}}</a></li>
     <li><a href="{{ url('/terms') }}">{{ __(trans('app.Terms Of Use'))}}</a></li>
   </ul>
 </div>
 <div class="col-12 col-sm-12 col-md-4 col-lg-4 social_media">
  <h1 class="margin_bottom20">{{ __(trans('app.Social Media'))}}</h1>
  <ul>
   <li><a href="<?php echo $social->facebooklink; ?>" target="_blank"><span class="fb_icon social_icon"><img src="{{ URL::asset('public/client_assets/images/fb.svg') }}" alt="fb" class="img-fluid svg fb"></span> &nbsp;&nbsp;<span class="align-middle">{{(trans('app.Facebook'))}}</span></a></li>
   <li><a href="<?php echo $social->twitterlink; ?>" target="_blank"><span class="twitter_icon social_icon"><img src="{{ URL::asset('public/client_assets/images/twitter.svg') }}" alt="fb" class="img-fluid svg twitter"></span>&nbsp;&nbsp; <span class="align-middle">{{(trans('app.Twitter'))}}</span></a></li>
   <li><a href="<?php echo $social->instagramlink; ?>" target="_blank"><span class="gplus_icon social_icon"><img src="{{ URL::asset('public/client_assets/images/instagram.svg') }}" alt="fb" class="img-fluid svg gplus"></span> &nbsp;&nbsp;<span class="align-middle">{{(trans('app.Instagram'))}}</span></a></li>
 </ul>
</div>
</div>
</div>
</footer>
<script src="{{ URL::asset('public/client_assets/js/jquery.min.js') }}"></script>
<script src="{{ URL::asset('public/client_assets/js/popper.min.js') }}"></script>
<script src="{{ URL::asset('public/client_assets/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('public/client_assets/js/custom.js') }}"></script>
<script type="text/javascript">
  var baseUrl = '<?php echo url('/admin/'); ?>';
  function setlang() {
    var lang = $("#language").val();
    window.location.href = baseUrl + "/lang/" + lang;
  }
</script>