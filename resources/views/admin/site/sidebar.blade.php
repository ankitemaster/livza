<!--**********************************
            Sidebar start
        ***********************************-->
        <div class="nk-sidebar">           
            <div class="nk-nav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="nav-label">Navigation</li>
                    <li>
                        <a class="has-arrow" href="{{ url('/admin/dashboard') }}" aria-expanded="false">
                            <i class="mdi mdi-speedometer"></i><span class="nav-text">{{trans('app.Dashboard')}}</span>
                        </a>
                    </li>
                    
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="mdi mdi-layers"></i><span class="nav-text">{{trans('app.Accounts')}}</span></a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('/admin/accounts') }}"><i class="fa fa-check-circle-o" aria-hidden="true"></i> {{trans('app.Approved')}}</a></li>
                            <li><a href="{{ url('/admin/accounts/pending') }}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{trans('app.Pending')}}</a></li>
                            <li><a href="{{ url('/admin/accounts/online') }}"><i class="fa fa-toggle-on" aria-hidden="true"></i> {{trans('app.Online users')}}</a></li>
                        </ul>
                    </li>
                    
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="mdi mdi-apps"></i><span class="nav-text">{{trans('app.Settings')}}</span></a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('admin.mainsettings') }}"><i class="fa fa-cogs" aria-hidden="true"></i> {{trans('app.Main')}}</a></li>
                            <li><a href="{{ route('admin.logosettings') }}"><i class="fa fa-picture-o" aria-hidden="true"></i> {{trans('app.Logo')}}</a></li>
                            <li><a href="{{ route('admin.socialsettings') }}"><i class="fa fa-envelope" aria-hidden="true"></i> {{trans('app.App & Social')}}</a></li>
                            <li><a href="{{ route('admin.purchasesettings') }}"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> {{trans('app.Payment')}}</a></li>
                            
                        </ul>
                    </li>
                    <li><a href="javascript:void()" aria-expanded="false"><i class="mdi mdi-file-document-box"></i> <span class="nav-text">{{trans('app.Helps')}}</span></a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('admin.helps.create') }}"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{trans('app.New Help query')}}</a></li>
                            <li><a href="{{ route('admin.helps.index') }}"><i class="fa fa-table" aria-hidden="true"></i> {{trans('app.Manage help')}}</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void()" aria-expanded="false"><i class="fa fa-diamond" aria-hidden="true"></i> <span class="nav-text">{{trans('app.Gems')}}</span></a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('admin.gems.create') }}"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{trans('app.New package')}}</a></li>
                            <li><a href="{{ route('admin.gems.index') }}"><i class="fa fa-table" aria-hidden="true"></i> {{trans('app.Manage gems')}}</a></li>
                        </ul>
                    </li>

                    <li><a href="javascript:void()" aria-expanded="false"><i class="fa fa-gift" aria-hidden="true"></i> <span class="nav-text">{{trans('app.Gifts')}}</span></a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('admin.gifts.create') }}"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{trans('app.New Gift')}}</a></li>
                            <li><a href="{{ route('admin.gifts.index') }}"><i class="fa fa-table" aria-hidden="true"></i> {{trans('app.Manage gifts')}}</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void()" aria-expanded="false"><i class="fa fa-diamond" aria-hidden="true"></i> <span class="nav-text">Subscription</span></a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('admin.subscriptions.create') }}"><i class="fa fa-plus-circle" aria-hidden="true"></i> New subscription</a></li>
                            <li><a href="{{ route('admin.subscriptions.index') }}"><i class="fa fa-table" aria-hidden="true"></i> Manage subscription</a></li>
                        </ul>
                    </li>
                     <li><a href="javascript:void()" aria-expanded="false"><i class="fa fa-diamond" aria-hidden="true"></i> <span class="nav-text">Prime slider</span></a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('admin.settings.slidercreate') }}"><i class="fa fa-plus-circle" aria-hidden="true"></i> New slider</a></li>
                            <li><a href="{{ route('admin.settings.sliderlist') }}"><i class="fa fa-table" aria-hidden="true"></i> Manage slider</a></li>
                            
                        </ul>
                    </li>
                    <li><a href="javascript:void()" aria-expanded="false"><i class="fa fa-book" aria-hidden="true"></i> <span class="nav-text">{{trans('app.Reports')}}</span></a>
                        <ul aria-expanded="false">
                           
                            <li><a href="{{ route('admin.reports.create') }}"><i class="mdi mdi-layers"></i> {{trans('app.Report titles')}}</a></li>
                            <li><a href="{{ route('admin.reports.reportlist') }}"><i class="mdi mdi-layers"></i> {{trans('app.User\'s Report')}}</a></li>
                        </ul>
                    </li>
                    

                </ul>
            </div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->



        