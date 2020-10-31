@extends('admin.layouts.sidebar')
<?php use Carbon\Carbon;?>
@section('content')
<!--**********************************
Content body start
***********************************-->
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/logosettings') }}">{{trans('app.Notification Settings')}}</a></li>
        </ol>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
</div>
<!-- row -->
<div class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="content-heading mt-2">{{trans('app.Apple Push Notification service')}}</h3>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-validation">
                        <form class="form-valide" action="{{action('Admin\SettingsController@updatenotificationsettings')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{trans('app.Voip Key')}} <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-2 mb-1 mr-5">
                                            <input type="file" class="dropify" data-default-file="" name="val-key" id="val-key" />
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            @if ($errors->has('val-key'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('val-key') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{trans('app.Voip Certificate')}} <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-2 mb-1 mr-5">
                                            <input type="file" class="dropify" data-default-file="" name="val-cert" id="val-cert" />
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            @if ($errors->has('val-cert'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('val-cert') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">{{trans('app.Voip PassPharse')}} <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-9">
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="val-pass" name="val-pass" placeholder="{{trans('app.Voip PassPharse')}}" value="{{$appsettings->voip_passpharse}}" maxlength="30">

                                            </div>
                                        </div>
                                        <div class="col-lg-2"></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9" id="titerr">
                                            @if ($errors->has('val-pass'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('val-pass') }}</strong>
                                            </span>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-9 ml-auto">
                                            <button type="submit" class="btn btn-primary">{{trans('app.Save')}}</button>
                                        </div>
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
<!-- dropify -->
<script src="{{ URL::asset('public/admin_assets/assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ URL::asset('public/admin_assets/main/js/plugins-init/dropify-init.js') }}"></script>
@endsection