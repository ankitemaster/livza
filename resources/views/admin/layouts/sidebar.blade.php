<?php
use App\Classes\MyClass;
$myClass = new MyClass();
$settings = $myClass->site_settings();
?>
@include('js-localization::head')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{$settings->sitename}} - {{$settings->meta_title}}</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('public/img/logo/'.$settings->favicon) }}">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="{{ URL::asset('public/admin_assets/assets/plugins/highlightjs/styles/darcula.css') }}">
    <link href="{{ URL::asset('public/admin_assets/main/css/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/admin_assets/assets/plugins/summernote/summernote.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/admin_assets/assets/plugins/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/admin_assets/assets/plugins/select2/css/select2.min.css') }}" media="screen" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('public/admin_assets/assets/plugins/jquery-confirm/css/jquery-confirm.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/admin_assets/assets/plugins/chartjs/css/Chart.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    <script type="text/javascript">
        var baseUrl = '<?php echo url('/admin/'); ?>';
    </script>
    <style type="text/css">
     .toast{
        display: none!important;
    }
    .toast-success {
        background-color: #4bad91 !important;
    }
    .toast-info {
        background-color: #4bad91 !important;
    }
    #toast-container > div {
        opacity:1 !important;
    }
</style>
<link href="{{ URL::asset('public/admin_assets/assets/plugins/toastr/css/toastr.min.css') }}" rel="stylesheet">
<!-- Common JS -->
<script src="{{ URL::asset('public/admin_assets/assets/plugins/common/common.min.js') }}"></script>
<script src="{{ URL::asset('public/admin_assets/assets/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ URL::asset('public/admin_assets/assets/plugins/moment/moment.min.js') }}"></script>
</head>
<body>
    <!--*******************
        Preloader start
        ********************-->
        <div id="preloader">
            <div class="spinner">
                <div class="spinner-a"></div>
                <div class="spinner-b"></div>
            </div>
        </div>
    <!--*******************
        Preloader end
        ********************-->
    <!--**********************************
        Main wrapper start
        ***********************************-->
        <div id="main-wrapper">
        <!--**********************************
            Nav header start
            ***********************************-->
            <div class="nav-header">
                <div class="brand-logo">
                    <a href="{{route('admin.dashboard')}}">
                        <b class="logo-abbr">{{substr($settings->sitename,0,1)}} </b>
                        <span class="brand-title">{{substr($settings->sitename,0,6)}} </span>
                    </a>
                </div>
                <div class="nav-control">
                    <div class="hamburger">
                        <span class="toggle-icon"><i class="icon-menu"></i></span>
                    </div>
                </div>
            </div>
        <!--**********************************
            Header start
            ***********************************-->
            <div class="header">
                <div class="header-content clearfix">
                    <div class="header-right">
                        <ul class="clearfix">
                            <li class="icons">
                                <a href="{{route('admin.notification')}}" class="">
                                    <i class="fa fa-paper-plane"></i>
                                </a>
                            </li>
                            <li class="icons">
                                <?php $lang = App::getLocale();?>
                                <select class="lang selectpicker form-control-sm" id="language" onChange="setlang()" data-width="auto">
                                    <option value="en" <?php echo $lang == 'en' ? 'selected' : ''; ?>>English</option>
                                    <option value="fr" <?php echo $lang == 'fr' ? 'selected' : ''; ?>>French</option>
                                    <option value="ar" <?php echo $lang == 'ar' ? 'selected' : ''; ?>>Arabic</option>
                                <!-- <option value="ar" <?php
                                ?>>Arabic</option> -->
                            </select>
                        </li>
                        <li class="icons">
                            <div class="user-img c-pointer">
                                <strong class="ml-2">{{ Auth::user()->name }} <span><i class="fa fa-caret-down ml-2"></i></span></strong>
                            </div>
                            <div class="drop-down dropdown-profile animated flipInX">
                                <div class="dropdown-content-body">
                                    <ul>
                                        <li><a href="{{route('admin.profile')}}">{{trans('app.Profile')}}</a>
                                        </li>
                                        <li><a href="{{route('admin.resetpassword')}}">{{trans('app.Reset password')}}</a>
                                        </li>
                                        <li><a class="sweet-prompt" href="{{ route('logout') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        {{trans('app.Logout')}}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="nk-sidebar">
    <div class="nk-nav-scroll">
        <ul class="metismenu" id="menu">
            <li>
                <a class="d-flex" href="{{ url('/admin/dashboard') }}" aria-expanded="false">
                    <i class="mdi mdi-view-dashboard align-self-center mr-2"></i><span class="nav-text align-self-center">{{trans('app.Home')}}</span>
                </a>
            </li>
            <li class="d-flex justify-content-center">
                <a class="d-flex" href="{{ url('/admin/statistics') }}" aria-expanded="false">
                    <i class="fa fa-area-chart align-self-center mr-2"></i><span class="nav-text align-self-center">{{trans('app.Statistics')}}</span>
                </a>
            </li>
            <li><a class="has-arrow d-flex" href="javascript:void()" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span class="nav-text align-self-center">{{trans('app.Accounts')}}</span></a>
                <ul aria-expanded="false">
                    <li><a href="{{ url('/admin/accounts') }}"><i class="fa fa-check align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.Approved')}}</span></a></li>
                    <li><a href="{{ url('/admin/accounts/pending') }}"><i class="fa fa-ban align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.Pending')}}</span></a></li>
                    <!-- <li><a href="{{ url('/admin/accounts/online') }}"><i class="fa fa-toggle-on align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.Online')}}</span></a></li> -->
                </ul>
            </li>
            <li><a class="has-arrow d-flex" href="javascript:void()" aria-expanded="false"><i class="mdi mdi-video"></i><span class="nav-text align-self-center">{{trans('app.Streams')}}</span></a>
                <ul aria-expanded="false">
                    <li><a href="{{ url('/admin/streams?status=active') }}"><i class="fa fa-check align align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.Active')}}</span></a></li>
                    <li><a href="{{ url('/admin/streams?status=blocked') }}"><i class="fa fa-ban align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.Blocked')}}</span></a></li>
                </ul>
            </li>
            <li class="d-flex justify-content-center"><a class="has-arrow d-flex" href="javascript:void()" aria-expanded="false"><i class="fa fa-cog align-self-center"></i><span class="nav-text align-self-center">{{trans('app.Settings')}}</span></a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('admin.mainsettings') }}"><i class="fa fa-cogs align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.General')}}</span></a></li>
                    <li><a href="{{ route('admin.logosettings') }}"><i class="fa fa-picture-o align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.Media')}}</span></a></li>
                    <li><a href="{{ route('admin.payments') }}"><i class="fa fa-money align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.Payments')}}</span></a></li>
                    <li><a href="{{ route('admin.socialsettings') }}"><i class="fa fa-link align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.Links')}}</span></a></li>
                    <li><a href="{{ route('admin.purchasesettings') }}"><i class="fa fa-credit-card-alt align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.Ads')}}</span></a></li>
                    <li><a href="{{ route('admin.emailsettings') }}"><i class="fa fa-envelope align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.Email')}}</span></a></li>
                    <li><a href="{{ route('admin.notificationsettings') }}"><i class="fa fa-paper-plane align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.Notification')}}</span></a></li>
                    <li><a href="{{ route('admin.hashtags') }}"><i class="fa fa-hashtag align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.Hashtags')}}</span></a></li>
                </ul>
            </li>
            <li class="d-flex justify-content-center"><a class="has-arrow d-flex" href="javascript:void()" aria-expanded="false"><i class="fa fa-id-card align-self-center" aria-hidden="true"></i> <span class="nav-text align-self-center">{{trans('app.Subscription')}}</span></a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('admin.subscriptions.create') }}"><i class="fa fa-plus-circle align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.New subscription')}}</span></a></li>
                    <li><a href="{{ route('admin.subscriptions.index') }}"><i class="fa fa-table align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.Manage subscription')}}</span></a></li>
                </ul>
            </li>
            <!-- Payment changes -->
            <li><a class="has-arrow d-flex" href="{{ url('/admin/payments') }}" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span class="nav-text align-self-center">{{trans('app.Payments')}}</span></a>
            </li>
            <li class="d-flex justify-content-center"><a class="has-arrow d-flex" href="javascript:void()" aria-expanded="false"><i class="fa fa-diamond align-self-center" aria-hidden="true"></i> <span class="nav-text align-self-center">{{trans('app.Gems')}}</span></a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('admin.gems.create') }}"><i class="fa fa-plus-circle align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.New package')}}</span></a></li>
                    <li><a href="{{ route('admin.gems.index') }}"><i class="fa fa-table align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.Manage gems')}}</span></a></li>
                </ul>
            </li>
            <li class="d-flex justify-content-center"><a class="has-arrow d-flex" href="javascript:void()" aria-expanded="false"><i class="fa fa-gift align-self-center" aria-hidden="true"></i> <span class="nav-text align-self-center">{{trans('app.Gifts')}}</span></a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('admin.gifts.create') }}"><i class="fa fa-plus-circle align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.New Gift')}}</span></a></li>
                    <li><a href="{{ route('admin.gifts.index') }}"><i class="fa fa-table align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.Manage gifts')}}</span></a></li>
                </ul>
            </li>
        </li>
        <li class="d-flex justify-content-center"><a class="has-arrow d-flex" href="javascript:void()" aria-expanded="false"><i class="fa fa-diamond align-self-center" aria-hidden="true"></i> <span class="nav-text align-self-center">{{trans('app.Sliders')}}</span></a>
            <ul aria-expanded="false">
                <li><a href="{{ route('admin.settings.slidercreate') }}"><i class="fa fa-plus-circle align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.New slider')}}</span></a></li>
                <li><a href="{{ route('admin.settings.sliderlist') }}"><i class="fa fa-table align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.Manage slider')}}</span></a></li>
            </ul>
        </li>
        <li class="d-flex justify-content-center"><a class="has-arrow d-flex" href="javascript:void()" aria-expanded="false"><i class="fa fa-book align-self-center" aria-hidden="true"></i> <span class="nav-text align-self-center">{{trans('app.Reports')}}</span></a>
            <ul aria-expanded="false">
                <li><a href="{{ route('admin.reports.create') }}" class="d-flex"><i class="mdi mdi-layers align-self-center mr-2"></i> <span class="align-self-center">{{trans('app.Report titles')}}</span></a></li>
                <li><a href="{{ route('admin.reports.reportlist') }}" class="d-flex"><i class="mdi mdi-layers align-self-center mr-2"></i><span class="align-self-center"> {{trans('app.Stream report')}} </span></a></li>
            </ul>
        </li>
        <li><a class="has-arrow d-flex" href="javascript:void()" aria-expanded="false"><i class="mdi mdi-file-document-box"></i> <span class="nav-text align-self-center">{{trans('app.Helps')}}</span></a>
            <ul aria-expanded="false">
                <!-- <li><a href="{{ route('admin.helps.create') }}"><i class="fa fa-plus-circle align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.New Help query')}}</span></a></li> -->
                <li><a href="{{ route('admin.helps.index') }}"><i class="fa fa-table align-self-center mr-2" aria-hidden="true"></i> <span class="align-self-center">{{trans('app.Manage help')}}</span></a></li>
            </ul>
        </li>
    </ul>
</div>
</div>
<div class="content-body">
    @yield('content')
    <script>
        @if(Session::has('notification'))
        var type = "{{ Session::get('notification.alert-type', 'info') }}";
        switch (type) {
            case 'info':
            toastr.info("{{ Session::get('notification.message') }}");
            break;
            case 'warning':
            toastr.warning("{{ Session::get('notification.message') }}");
            break;
            case 'success':
            toastr.success("{{ Session::get('notification.message') }}");
            break;
            case 'error':
            toastr.error("{{ Session::get('notification.message') }}");
            break;
        }
        {{ Session::forget('notification') }}
        @endif
    </script>
</div>
        <!--**********************************
            Content body end
            ***********************************-->
        <!--**********************************
            Footer start
            ***********************************-->
            <div class="footer">
                <div class="copyright">
                    <p>{{$settings->copyrights}}</p>
                </div>
            </div>
        <!--**********************************
            Footer end
            ***********************************-->
        </div>
    <!--**********************************
        Main wrapper end
        ***********************************-->
    <!--**********************************
        Scripts
        ***********************************-->
        <script>
            var lang = '<?php echo App::getLocale(); ?>';
        </script>
        <!-- Custom script -->
        <script src="{{ URL::asset('public/admin_assets/main/js/custom.min.js') }}"></script>
        <script src="{{ URL::asset('public/admin_assets/main/js/settings.js') }}"></script>
        <script src="{{ URL::asset('public/admin_assets/main/js/quixnav.js')}}"></script>
        <script src="{{ URL::asset('public/admin_assets/main/js/styleSwitcher.js')}}"></script>
        <script src="{{ URL::asset('public/admin_assets/assets/plugins/highlightjs/highlight.pack.min.js')}}"></script>
        <script src="{{ URL::asset('public/admin_assets/main/js/admin.js')}}"></script>
        <script src="{{ URL::asset('public/admin_assets/assets/plugins/summernote/js/summernote.min.js')}}"></script>
        <script src="{{ URL::asset('public/admin_assets/main/js/plugins-init/summernote-init.js')}}"></script>
        <script src="{{ URL::asset('public/admin_assets/assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
        <script src="{{ URL::asset('public/admin_assets/assets/plugins/jquery-confirm/js/jquery-confirm.min.js') }}"></script>
        <script src="{{ URL::asset('public/admin_assets/assets/plugins/chartjs/js/Chart.min.js') }}"></script>
        <script>
            hljs.initHighlightingOnLoad();
            function changeStatus(id, status) {
                if (status == 1) {
                    var check = confirm("<?php echo trans('app.Are you going to active this account  ?'); ?>");
                } else {
                    var check = confirm("<?php echo trans('app.Are you going to inactive this account  ?'); ?>");
                }
                if (check == true) {
                    $.ajax({
                        url: baseUrl + "/accounts/changestatus/" + id,
                        type: "get",
                        data: { status: status },
                        success: function(responce) {
                            location.href = baseUrl + responce;
                        }
                    });
                }
            }
            function repchangeStatus(id, status, rid) {
                if (status == 1) {
                    var check = confirm("<?php echo trans('app.Are you going to active this account  ?'); ?>");
                } else {
                    var check = confirm("<?php echo trans('app.Are you going to inactive this account  ?'); ?>");
                }
                if (check == true) {
                    $.ajax({
                        url: baseUrl + "/reports/changestatus/" + id,
                        type: "get",
                        data: { status: status },
                        success: function(responce) {
                            location.href = baseUrl + responce + rid;
                        }
                    });
                }
            }
            $(document).ready(function() {
                $('.search_locat').select2();
                $('a.del_activity_btn').confirm({
                    content: "<?php echo trans('app.Are you sure to delete this item?'); ?>",
                    type: 'purple',
                    autoClose: '<?php echo trans('app.Cancel'); ?>|8000',
                    buttons: {
                        <?php echo trans('app.Ok'); ?>: {
                            btnClass: 'btn-primary',
                            action: function(){
                                location.href = this.$target.attr('href');
                            }
                        },
                        <?php echo trans('app.Cancel'); ?>: {
                            btnClass: 'btn-default',
                        },
                    }
                });
                $('a.activity_btn').confirm({
                    content: "<?php echo trans('app.Are you sure to do this action?'); ?>",
                    type: 'purple',
                    buttons: {
                        <?php echo trans('app.Ok'); ?>: {
                            btnClass: 'btn-primary',
                            action: function(){
                                location.href = this.$target.attr('href');
                            }
                        },
                        <?php echo trans('app.Cancel'); ?>: {
                            btnClass: 'btn-default',
                        },
                    }
                });
                $('a.action_btn_act').confirm({
                    content: "<?php echo trans('app.Are you sure to active this account?'); ?>",
                    type: 'purple',
                    autoClose: '<?php echo trans('app.Cancel'); ?>|8000',
                    buttons: {
                        <?php echo trans('app.Ok'); ?>: {
                            btnClass: 'btn-primary',
                            action: function(){
                                location.href = this.$target.attr('href');
                            }
                        },
                        <?php echo trans('app.Cancel'); ?>: {
                            btnClass: 'btn-default',
                        },
                    }
                });
                $('a.action_btn_inact').confirm({
                    content: "<?php echo trans('app.Are you sure to inactive this account?'); ?>",
                    type: 'purple',
                    autoClose: '<?php echo trans('app.Cancel'); ?>|8000',
                    buttons: {
                        <?php echo trans('app.Ok'); ?>: {
                            btnClass: 'btn-primary',
                            action: function(){
                                location.href = this.$target.attr('href');
                            }
                        },
                        <?php echo trans('app.Cancel'); ?>: {
                            btnClass: 'btn-default',
                        },
                    }
                });
                $('a.action_btn_inact_stream').confirm({
                    content: "<?php echo trans('app.Are you sure to inactive this stream?'); ?>",
                    type: 'purple',
                    autoClose: '<?php echo trans('app.Cancel'); ?>|8000',
                    buttons: {
                        <?php echo trans('app.Ok'); ?>: {
                            btnClass: 'btn-primary',
                            action: function(){
                                location.href = this.$target.attr('href');
                            }
                        },
                        <?php echo trans('app.Cancel'); ?>: {
                            btnClass: 'btn-default',
                        },
                    }
                });
                $('a.action_btn_act_stream').confirm({
                    content: "<?php echo trans('app.Are you sure to active this stream?'); ?>",
                    type: 'purple',
                    autoClose: '<?php echo trans('app.Cancel'); ?>|8000',
                    buttons: {
                        <?php echo trans('app.Ok'); ?>: {
                            btnClass: 'btn-primary',
                            action: function(){
                                location.href = this.$target.attr('href');
                            }
                        },
                        <?php echo trans('app.Cancel'); ?>: {
                            btnClass: 'btn-default',
                        },
                    }
                });
            });
        </script>
    </body>
    </html>