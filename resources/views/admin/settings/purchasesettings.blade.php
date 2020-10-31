@extends('admin.layouts.sidebar')
<?php use Carbon\Carbon; ?>
@section('content')
<!--**********************************
            Content body start
        ***********************************-->
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/purchasesettings') }}">{{trans('app.Ads Settings')}}</a></li>
        </ol>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
</div>
<!-- row -->
<div class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="content-heading mt-2">{{trans('app.Ads Settings')}}</h3>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-validation">
                        <form class="form-valide" action="{{action('Admin\SettingsController@purchaseupdate')}}" method="post" enctype="multipart/form-data" onSubmit="return validate_purchase()">
                            @csrf
                            <div class="row">
                                <div class="col-xl-12">
                                    <!-- <div class="form-group row">
                                        <div class="col-lg-2"></div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><STRONG>{{trans('app.Purchase License Token')}} <span class="text-danger">*</span></STRONG></span>
                                                </div>
                                                <input type="text" class="form-control" id="val-license_token" name="val-license_token" value="{{$set_det->license_token}}" placeholder="{{trans('app.Provide inapp license token')}}" maxlength="60">
                                            </div>
                                        </div>
                                        <div class="col-lg-2"></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-4"></div>
                                        <div class="col-lg-8" id="titerr" style="color: #fd397a;">
                                            @if ($errors->has('val-license_token'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('val-license_token') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div> -->
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">
                                        </label>
                                        <div class="col-md-8">
                                            <h3 class="content-heading mt-2">{{trans('app.Google Adsense Settings')}}</h3>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">
                                        </label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><STRONG>{{trans('app.Google ads')}} </STRONG></span>
                                                </div>
                                                <div class="form-check form-check-inline" style="margin-left: 0.75rem;">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" id="val-adsenable" name="val-adsenable" value="1" <?php if (isset($set_det->adsenable) && $set_det->adsenable == '1') {
                                                                                                                                                            echo 'checked=checked';
                                                                                                                                                        } ?>>
                                                        {{trans('app.Enable')}}
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline" style="margin-left: 0.75rem;">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" id="val-adsenable" name="val-adsenable" value="0" <?php if (isset($set_det->adsenable) && $set_det->adsenable == '0') {
                                                                                                                                                            echo 'checked=checked';
                                                                                                                                                        } ?>>
                                                        {{trans('app.Disable')}}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9" id="" style="color: #fd397a">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-2"></div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><STRONG>{{trans('app.Adsense key')}}</STRONG></span>
                                                </div>
                                                <input type="text" class="form-control" id="val-adskey" name="val-adskey" value="@if(isset($set_det->adskey)){{$set_det->adskey}}@endif" placeholder="{{trans('app.Provide google adsense key')}}" maxlength="150">
                                            </div>
                                        </div>
                                        <div class="col-lg-2"></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9" id="" style="color: #fd397a">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-2"></div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><STRONG>{{trans('app.Video Adsense key')}}</STRONG></span>
                                                </div>
                                                <input type="text" class="form-control" id="val-video-adskey" name="val-video-adskey" value="@if(isset($set_det->videoadskey)){{$set_det->videoadskey}}@endif" placeholder="{{trans('app.Provide google video adsense key')}}" maxlength="150">
                                            </div>
                                        </div>
                                        <div class="col-lg-2"></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9" id="adskeyerr" style="color: #fd397a">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-2"></div>
                                        <div class="col-lg-8 ml-auto">
                                            <button type="submit" class="btn btn-primary">{{trans('app.Update')}}</button>
                                        </div>
                                        <div class="col-lg-2"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection