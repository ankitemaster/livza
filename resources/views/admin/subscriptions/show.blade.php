@extends('admin.layouts.sidebar')
   <?php 
use Carbon\Carbon; 
use App\Classes\MyClass;
$myClass = new MyClass();
$settings = $myClass->site_settings();
?>@section('content')
  <!--**********************************
            Content body start
        ***********************************-->
        
        <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
                            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/subscriptions/show/'.$subs->id) }}">{{trans('app.View Subscription')}}</a></li>
                        </ol>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
                    
                    </div>
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <h3 class="content-heading" style="margin-bottom: 0px;margin-top: 0px;">{{trans('app.View Subscription')}}</h3> 
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <a class="btn btn-primary mb-2" href="{{ URL::previous() }}">{{trans('app.Back')}}</a>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">{{$subs->subs_title}}</h4>
                                <div class="bootstrap-media">
                                    <div class="media">
                                        <div class="media-body">
                                            <img src="{{url('/public/img/gems.png')}}" style="width: 20px;height: 18px;">  {{$subs->subs_gems}} / <img src="{{url('/public/img/coin.png')}}" style="width: 18px;height: 16px;">  {{$subs->subs_price}}
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="card-footer d-sm-flex justify-content-between">
                                <div class="card-footer-link mb-4 mb-sm-0">
                                    <p class="card-text text-dark d-inline" style="color: #fff!important">{{trans('app.Last updated')}} {{Carbon::createFromTimestamp(strtotime($subs->updated_at->format('r')))->diffForHumans()}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
      

        <!--**********************************
            Content body end
        ***********************************-->
@endsection