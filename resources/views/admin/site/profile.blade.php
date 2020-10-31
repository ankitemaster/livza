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
                            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/profile') }}">{{trans('app.Profile')}}</a></li>
                        </ol>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
                    
                    </div>
                            <!-- row -->

                            <div class="container">
                            <div class="row">
                            <div class="col-12">
                            <h3 class="content-heading mt-2">{{trans('app.Profile')}}</h3>
                        </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <div class="form-validation">
                            <form class="form-valide" action="{{action('Admin\AdminController@profileupdate')}}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">{{trans('app.Name')}} <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" id="val-username" name="val-username" value="{{$details->name}}" placeholder="{{trans('app.Enter a Name..')}}">
                                                @if ($errors->has('val-username'))
                                                <span class="help-block text-danger">
                                                    {{ $errors->first('val-username') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">{{trans('app.Email')}} <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" id="val-email" name="val-email" placeholder="{{trans('app.Your valid email..')}}" value="{{$details->email}}" >
                                                @if ($errors->has('val-email'))
                                                <span class="help-block text-danger">
                                                    {{ $errors->first('val-email') }}
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

<!-- Jquery Validation -->
{{ Html::script('public/plugins/jquery-validation/jquery.validate.min.js') }}

<!-- form validation -->
{{ Html::script('public/js/plugins-init/jquery.validate-init.js') }}
@endsection 