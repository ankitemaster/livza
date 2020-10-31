@extends('admin.layouts.sidebar')

@section('content')

<?php
use Carbon\Carbon;
use App\Classes\MyClass;
$myClass = new MyClass();
$settings = $myClass->site_settings();
?>
  <!--**********************************
            Content body start
        ***********************************-->
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
                    <li class="breadcrumb-item active"><a href="{{ URL::to('admin/accounts/show/'.$accounts->id) }}">{{trans('app.View Accounts')}}</a></li>
                    @if($type == 'follower')
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('app.View Followers')}}</a></li>
                    @else<li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('app.View Followings')}}</a></li>
                    @endif
                </ol>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
        </div>

            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                @if($type == 'follower')
                    <h3 class="content-heading" style="margin-bottom: 0px;margin-top: 0px;">{{trans('app.View')}} {{$accounts->acct_name}}'s {{trans('app.Followers')}}Followers</h3> 
                @else
                    <h3 class="content-heading" style="margin-bottom: 0px;margin-top: 0px;">{{trans('app.View')}} {{$accounts->acct_name}}'s {{trans('app.Followings')}}</h3> 
                @endif
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <a class="btn btn-primary mb-2" href="{{ URL::to('admin/accounts/show/'.$accounts->id) }}">{{trans('app.Back')}}</a>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container">
            <div class="row mb-4 mt-4">
            @if(!empty($data))  
                <?php $id = 1; ?>
                @foreach($data as $followerr)
                    <div class="col-xl-3 col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <?php 
                                            $image = public_path().'/img/accounts/'.$followerr['acct_photo'];
                                            if (!file_exists($image)) {
                                                $image = '/public/img/user.png';
                                            }
                                            else
                                            {
                                                $image = '/public/img/accounts/'.$followerr['acct_photo'];
                                            }
                                        ?>
                                        <a href="{{ URL::to('admin/accounts/show/'.$followerr['id']) }}">   
                                        <div class="media align-items-center">
                                        
                                            {{ Html::image($image, 'user', array('class' => 'mr-3 rounded-circle mr-0 mr-sm-3','width' => '80', 'height' => '80')) }}
                                            <div class="media-body">
                                                <h3 class="mb-0" style="word-break: break-all;font-size: larger;">{{$followerr['acct_name']}}</h3>
                                                
                                            </div>
                                        </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php $id++; ?>
                @endforeach
               
                @else
                <div class="col-md-12">                                              
                    @if($type == 'follower')
                    <center><i class="fa fa-exclamation-triangle" aria-hidden="true" style="    color: yellow;"></i> {{trans('app.No followers')}}</center>
                    @else
                    <center><i class="fa fa-exclamation-triangle" aria-hidden="true" style="    color: yellow;"></i> {{trans('app.No followings')}}</center>
                    @endif
                </div>
                @endif
            </div>
            <div class="pagination-wrapper" style="float: right;"> {!! $datarender->render() !!} </div>
            </div>

        
        <!--**********************************
            Content body end
        ***********************************-->
@endsection