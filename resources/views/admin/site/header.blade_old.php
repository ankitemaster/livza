<div class="nav-header">
            <div class="brand-logo">
                <a href="{{route('admin.dashboard')}}">
                    <b class="logo-abbr">R </b>
                    <span class="brand-title"><?php echo $settings->sitename; ?> <!-- <img src="{{url('/public/img/logo/'.$settings->logo)}}" style="width: auto;height: 35px;"> --></span>
                </a>
            </div>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="toggle-icon"><i class="icon-menu"></i></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content clearfix">

               <!--  <div class="header-left">
                    <div class="header-search icons">
                        <span class="header-magnifier"><i class="icon-magnifier"></i> </span>
                        <form class="drop-down">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search Here">
                                <div class="input-group-append">
                                    <span class="input-group-text pl-3 pr-3">Search</span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> -->
                <div class="header-right">

                    <ul class="clearfix">
                        <!-- <li class="icons">
                            <a href="javascript:void(0)" class="">
                                <i class="mdi mdi-bell-outline"></i>
                                <span class="badge badge-primary">3</span>
                            </a>
                            <div class="drop-down animated flipInX dropdown-notfication">
                                <div class="dropdown-content-body">
                                    <ul>
                                        <li>
                                            <a href="javascript:void()">
                                                <span class="mr-3 avatar-icon bg-success-lighten-2"><i class="icon-envelope"></i></span>
                                                <div class="notification-content">
                                                    <h5 class="notification-heading">New message received</h5>
                                                    <span class="notification-text">One hour ago</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void()">
                                                <span class="mr-3 avatar-icon bg-danger-lighten-2"><i class="icon-user"></i></span>
                                                <div class="notification-content">
                                                    <h5 class="notification-heading">New account opened</h5>
                                                    <span class="notification-text">One hour ago</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void()">
                                                <span class="mr-3 avatar-icon bg-success-lighten-2"><i class="icon-like"></i></span>
                                                <div class="notification-content">
                                                    <h5 class="notification-heading">Liked our post</h5>
                                                    <span class="notification-text">One hour ago</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void()">
                                                <span class="mr-3 avatar-icon bg-danger-lighten-2"><i class="icon-calender"></i></span>
                                                <div class="notification-content">
                                                    <h5 class="notification-heading">Event Started</h5>
                                                    <span class="notification-text">One hour ago</span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                    <a class="d-flex justify-content-between bg-primary px-4 text-white" href="javascript:void()">
                                        <span>All Notifications</span>
                                        <span><i class="mdi mdi-file-document-box"></i></span>
                                    </a>
                                </div>
                            </div>
                        </li> -->
                        <li class="icons">
                            <?php $lang = App::getLocale();?>
                            <select class="selectpicker" id="language" onChange="setlang()" data-width="auto">
                                <option value="en" <?php echo $lang == 'en' ? 'selected' : ''; ?>>English</option>
                                <option value="fr" <?php echo $lang == 'fr' ? 'selected' : ''; ?>>French</option>
                                <!-- <option value="ar" <?php //echo $lang == 'ar' ? 'selected' : ''; ?>>Arabic</option> -->
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
                                        <li><a class="sweet-prompt del_activity_btn" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
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
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

