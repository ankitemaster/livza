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
            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/primesettings') }}">{{ __("Prime Settings")}}</a></li>
        </ol>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
       
    </div>
            <!-- row -->

            <div class="container">
            <div class="row">
            <div class="col-12">
            <h3 class="content-heading mt-2">{{ __("Prime Settings")}}</h3>
        </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                               
                                <div class="form-validation">
                                    <form class="form-valide" action="{{action('Admin\SettingsController@primesettingsupdate')}}" method="post" enctype="multipart/form-data" onSubmit="return validateprimedata()">
                                        @csrf
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label" >Prime price <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="val-price" name="val-price" value="{{$details['prime_price']}}" placeholder="Give prime price. Eg: $ 20" maxlength="50" value="{{ old('val-price') }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9" id="priceerr" style="color: #fd397a">
                                                        @if ($errors->has('val-price'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('val-price') }}</strong>
                                                        </span>
                                                    @endif
                                                    </div>
                                                 </div>

                                                 <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label" >Prime availability <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="val-duration" name="val-duration" value="{{$details['prime_availability']}}" placeholder="Give prime duration. Eg: 1 month" maxlength="50" value="{{ old('val-duration') }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9" id="durationerr" style="color: #fd397a">
                                                        @if ($errors->has('val-duration'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('val-duration') }}</strong>
                                                        </span>
                                                    @endif
                                                    </div>
                                                 </div>

                                                 <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">No of Gems <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="val-gems" name="val-gems" value="{{$details['no_of_gem']}}" placeholder="Give number of gems. Eg: 200 gems" maxlength="50" value="{{ old('val-gems') }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9" id="gemserr" style="color: #fd397a">
                                                        @if ($errors->has('val-gems'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('val-gems') }}</strong>
                                                        </span>
                                                    @endif
                                                    </div>
                                                 </div>
                                                
                                                   

                                                <div class="form-group row">
                                                    <div class="col-lg-9 ml-auto">
                                                        <button type="submit" class="btn btn-primary">Save</button>
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
         <script src="{{ URL::asset('admin_assets/assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/main/js/plugins-init/dropify-init.js') }}"></script>
    <style type="text/css">
            .note{
                margin-top: 0px;
                margin-bottom: 0px;
                color: #fff;
                padding: 0.375rem 0.75rem;
                width: 100%;
                height: 85px;
            }
        </style>

@endsection

