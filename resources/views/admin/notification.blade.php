@php
use App\Classes\MyClass;
$myClass = new MyClass();
$settings = $myClass->site_settings();
@endphp
@extends('admin.layouts.sidebar')
@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/notification') }}">{{trans('app.Notifications')}}</a></li>
        </ol>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
</div>
<!-- row -->
<div class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="content-heading mt-2">{{trans('app.Push Notification')}}</h3>
        </div>
    </div>
    <!-- row -->
    <div class="container">
        <div class="row">
            <div class="col-xl-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{trans('app.Push notification for IOS')}}</h5>
                        <form action="{{url('admin/dashboard/sendalert/ios')}}" class="" method="get" onsubmit="return validatemsg()">
                            <div class="form-group">
                                <textarea class="form-control" name="msg" id="msg" cols="30" rows="6" placeholder="{{trans('app.Type new notification message')}}" style="background: rgba(0, 0, 0, 0.4);"></textarea>
                                @if ($errors->has('msg'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('msg') }}</strong>
                                </span>
                                @endif
                                <span class="text-danger" id="msgerr"></span>
                            </div>
                            <!-- <div class="form-group">
                                            <label>Select notification type:</label>
                                            <select class="form-control" id="ntype" name="ntype">
                                                <option value="normal">Normal</option>
                                                <option value="alert">Alert</option>
                                                <option value="offer">Offers</option>
                                                <option value="update">Update</option>
                                            </select>
                                        </div> -->
                            <div class="align-items-center">
                                <button class="btn btn-primary px-3">{{trans('app.Send')}}</button>
                                <div style="float: right;">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <p class="card-text d-inline text-danger">{{trans('app.Note : This notification will be send to all IOS device users')}}</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{trans('app.Push notification for Android')}}</h5>
                        <form action="{{url('admin/dashboard/sendalert/android')}}" class="" method="get" onsubmit="return validateandroidmsg()">
                            <div class="form-group">
                                <textarea class="form-control" name="msgg" id="msgg" cols="30" rows="6" placeholder="{{trans('app.Type new notification message')}}" style="background: rgba(0, 0, 0, 0.4);"></textarea>
                                @if ($errors->has('msgg'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('msgg') }}</strong>
                                </span>
                                @endif
                                <span class="text-danger" id="msgerrr"></span>
                            </div>
                            <!-- <div class="form-group">
                                            <label>Select notification type:</label>
                                            <select class="form-control" id="ntypee" name="ntype">
                                                <option value="normal">Normal</option>
                                                <option value="alert">Alert</option>
                                                <option value="offer">Offers</option>
                                                <option value="update">Update</option>
                                            </select>
                                        </div> -->
                            <div class="align-items-center">
                                <button class="btn btn-primary px-3">{{trans('app.Send')}}</button>
                                <div style="float: right;">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <p class="card-text d-inline text-danger">{{trans('app.Note : This notification will be send to all Android device users')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ URL::asset('public/admin_assets/main/server/client.js')}}"></script>
    <style>
        .test {
            position: absolute;
            bottom: -20px;
            left: calc(50% - 10px);
            display: block;
        }
    </style>
    @endsection