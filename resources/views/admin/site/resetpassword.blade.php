@extends('admin.layouts.sidebar')
<?php use Carbon\Carbon; ?>
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        

                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __("Home")}}</a></li>
                            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/resetpassword') }}">{{trans('app.Reset Password')}}</a></li>
                        </ol>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
                    
                    </div>
                            <!-- row -->

                            <div class="container">
                            <div class="row">
                            <div class="col-12">
                            <h3 class="content-heading mt-2">{{trans('app.Reset Password')}}</h3>
                        </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                               
                                <div class="form-validation">
                                    <form class="form-valide" action="{{action('Admin\AdminController@resetpass')}}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-xl-12">

                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label" >{{trans('app.Old Password')}} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="password" class="form-control" id="val-oldpassword" name="val-oldpassword" placeholder="{{trans('app.Enter your Old Password')}}"
                                                        " value="{{ old('val-oldpassword') }}" maxlength="40">
                                                    </div>
                                                   
                                                </div>
                                                 <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9">
                                                         @if ($errors->has('val-oldpassword'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('val-oldpassword') }}</strong>
                                                        </span>
                                                    @endif
                                                    </div>
                                                 </div>
                                                
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label" >{{trans('app.New Password')}} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="password" class="form-control" id="val-password" name="val-password" placeholder="{{trans('app.Enter your New Password')}}" value="{{ old('val-password') }}" maxlength="40">
                                                    </div>
                                                </div>
                                                 <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9">
                                                         @if ($errors->has('val-password'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('val-password') }}</strong>
                                                        </span>
                                                    @endif
                                                    </div>
                                                 </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label" >{{trans('app.Confirm Password')}} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="password" class="form-control" id="val-confirm-password" name="val-confirm-password" placeholder="{{trans('app.Enter your Confirm Password')}}"  value="{{ old('val-confirm-password') }}" maxlength="40">
                                                    </div>
                                                </div>
                                                 <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9">
                                                         @if ($errors->has('val-confirm-password'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('val-confirm-password') }}</strong>
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
            
       
@endsection

