@php
use App\Classes\MyClass;
$myClass = new MyClass();
$settings = $myClass->site_settings();
@endphp
@extends('admin.layouts.sidebar')
@section('content')
<div class="container">
    <div class="row">&nbsp;</div>
    <div class="row mb-4 mt-4">
        <div class="col-xl-3 col-lg-6">
            <a href="{{ url('/admin/accounts') }}">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">{{trans('app.Accounts')}}</h5>
                                <span class="h2 font-weight-bold mb-0" id="total_accounts">{{ $total_accounts }}</span>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span class="text-success mr-2"><i class="fa fa-female"></i> <span
                                    id="female_per">{{ $female_per }}</span>%</span>
                            <span class="text-nowrap">{{trans('app.of females')}}</span>
                        </p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6">
            <a href="{{ url('/admin/streams') }}">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">
                                    {{trans('app.Streams')}}{{ __('') }}</h5>
                                <span class="h2 font-weight-bold mb-0" id="random_chats">{{ $total_streams }}</span>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span class="text-danger mr-2"><i class="fa fa-video-camera"></i> <span
                                    id="random_per">{{ $today_stream_per }}</span>%</span>
                            <span class="text-nowrap">{{trans('app.of today')}}</span>
                        </p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6">
            <a href="{{ url('/admin/statistics') }}">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">{{trans('app.Subscribers')}}</h5>
                                <span class="h2 font-weight-bold mb-0" id="subscribers">{{ $subscribers }}</span>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span class="text-warning mr-2"><i class="fa fa-bookmark-o"></i> <span
                                    id="sub_per">{{ $sub_per }}</span>%</span>
                            <span class="text-nowrap">{{trans('app.of accounts')}}</span>
                        </p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6">
            <a href="{{ url('/admin/statistics') }}">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">{{trans('app.Countries')}}</h5>
                                <span class="h2 font-weight-bold mb-0"
                                    id="total_countries">{{ $total_countries }}</span>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span class="text-success mr-2"><i class="fa fa-globe"></i> <span
                                    id="mostused_per">{{ $mostused_per }}</span>%</span>
                            <span class="text-nowrap"> {{trans('app.of')}} <span
                                    id="mostused_country">{{ $mostused_country}}</span> </span>
                        </p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row mb-4 mt-4">
        <div class="col-xl-3 col-lg-6">
            <a href="{{ url('/admin/reports/reportlist') }}">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">{{trans('app.Users Reports')}}
                                </h5>
                                <span class="h2 font-weight-bold mb-0" id="user_report">{{ $user_report }}</span>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span class="text-danger mr-2"><i class="fa fa-users"></i> <span
                                    id="user_report_per">{{ $user_report_per }}</span>%</span>
                            <span class="text-nowrap">{{trans('app.of today')}}</span>
                        </p>
                    </div>
                </div>
            </a>

        </div>
        <div class="col-xl-3 col-lg-6">
            <a href="{{ url('/admin/statistics') }}">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">{{trans('app.Transactions')}}</h5>
                                <span class="h2 font-weight-bold mb-0"
                                    id="total_transcations">{{ $total_transcations }}</span>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span class="text-warning mr-2"><i class="fa fa-credit-card"></i> <span
                                    id="today_transt_per">{{ $today_transt_per }}</span>%</span>
                            <span class="text-nowrap">{{trans('app.of today')}}</span>
                        </p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6">
            <a href="{{ url('/admin/statistics') }}">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">{{trans('app.Gems Purchases')}}
                                </h5>
                                <span class="h2 font-weight-bold mb-0"
                                    id="gems_transcations">{{ $gems_transcations }}</span>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span class="text-success mr-2"><i class="fa fa-diamond"></i> <span
                                    id="gems_transt_per">{{ $gems_transt_per }}</span>%</span>
                            <span class="text-nowrap">{{trans('app.of transactions')}}</span>
                        </p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6">
            <a href="{{ url('/admin/statistics') }}">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">{{trans('app.Subscriptions') }}
                                </h5>
                                <span class="h2 font-weight-bold mb-0"
                                    id="membership_transcations">{{ $membership_transcations }}</span>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span class="text-warning mr-2"><i class="fa fa-tag"></i> <span
                                    id="membership_transt_per">{{ $membership_transt_per }}</span>%</span>
                            <span class="text-nowrap">{{trans('app.of transactions')}}</span>
                        </p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-xxl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{trans('app.Leaderboard')}}</h4>
                    <div class="recent-transaction table-responsive">
                        <table class="table mb-0 table-responsive-tiny">
                            <thead>
                                <tr>
                                    <th>{{trans('app.S.no')}}</th>
                                    <th scope="col">{{trans('app.Name')}}</th>
                                    <th scope="col">{{trans('app.Streams')}} </th>
                                    <th scope="col">{{trans('app.Gems')}}</th>
                                    <th scope="col">{{trans('app.Gifts')}} </th>
                                    <th scope="col">{{trans('app.From')}} </th>
                                    <th scope="col">{{trans('app.Membership')}} </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $id =1; @endphp
                                @foreach($leaderboard as $leader)
                                <tr>
                                    <td>{{$id}}</td>
                                    <td><a href="{{ URL::to('admin/accounts/show/' .$leader->id) }}"
                                            target="_blank"><strong>{{substr($leader->acct_name,0,15)}}</strong></a>
                                    </td>
                                    <td>{{$leader->acct_streams}}</td>
                                    <td>{{$leader->acct_gems}}</td>
                                    <td>{{$leader->acct_gifts}}</td>
                                    <td>@if(!empty($leader->acct_location)){{$leader->acct_location}}@else{{'global'}}@endif</td>
                                    <td>@if($leader->acct_membership=="sub") <span
                                            class="label mb-2 mb-xl-0 label-primary">{{trans('app.Prime')}} </span>
                                        @else <span
                                            class="label mb-2 mb-xl-0 label-secondary">{{trans('app.Non Prime')}}
                                        </span>@endif</td>
                                </tr>
                                @php $id++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ URL::asset('public/admin_assets/main/server/client.js')}}"></script>
@endsection