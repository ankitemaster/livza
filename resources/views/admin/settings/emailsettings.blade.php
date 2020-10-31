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
            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/emailsettings') }}">{{trans('app.Email Settings')}}</a></li>
        </ol>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
       
    </div>
    <!-- row -->

    <div class="container">
        <div class="row">
        <div class="col-12">
            <h3 class="content-heading mt-2">{{trans('app.Email Settings')}}</h3>
        </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <div class="form-validation">
                            <form class="form-valide" action="{{action('Admin\SettingsController@emailsettingsupdate')}}" method="post" enctype="multipart/form-data" onSubmit="return validatemaildata()">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">{{trans('app.Mail driver')}} <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" id="val-driver" name="val-driver" value="@if(isset($details->driver)){{$details->driver}}@endif" placeholder="{{trans('app.Enter Mail driver')}}" maxlength="30" value="{{ old('val-driver') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-9" id="drivererr" style="color: #fd397a">
                                                @if ($errors->has('val-driver'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('val-driver') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">{{trans('app.Host name')}} <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" id="val-host" name="val-host" value="@if(isset($details->host)){{$details->host}}@endif" placeholder="{{trans('app.Enter Host name')}}" maxlength="100">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-9" id="hosterr" style="color: #fd397a">
                                                @if ($errors->has('val-host'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('val-host') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">{{trans('app.Port no')}} <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" id="val-port" name="val-port" value="<?php if (isset($details->port) && $details->port != '') {
                                                    echo $details->port;
                                                } else {
                                                    echo "0";
                                                }?>" placeholder="{{trans('app.Enter port number')}}" maxlength="6" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-9" id="porterr" style="color: #fd397a">
                                                @if ($errors->has('val-port'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('val-port') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">{{trans('app.Username')}} <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" id="val-username" name="val-username" value="@if(isset($details->username)){{$details->username}}@endif" placeholder="{{trans('app.Enter mail username')}}" maxlength="100">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-9" id="usernameerr" style="color: #fd397a">
                                                @if ($errors->has('val-username'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('val-username') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">{{trans('app.Password')}} <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" id="val-password" name="val-password" value="@if(isset($details->password)){{$details->password}}@endif" placeholder="{{trans('app.Enter mail password')}}" maxlength="100">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-9" id="passworderr" style="color: #fd397a">
                                                @if ($errors->has('val-password'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('val-password') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">{{trans('app.ssl encryption')}} <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                            <div class="form-check form-check-inline">
                                                        <label class="form-check-label">
                                                          
                                                            <input type="radio" class="form-check-input" id="val-encryption" name="val-encryption" value="1" <?php if($details->encryption == '1'){echo 'checked=checked';} ?>>
                                                            
                                                            {{trans('app.Enable')}}

                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <label class="form-check-label">
                                                           
                                                             <input type="radio" class="form-check-input"  id="val-encryption" name="val-encryption" value="0"<?php if($details->encryption == '0'){echo 'checked=checked';} ?>>
                                                             
                                                             {{trans('app.Disable')}}

                                                        </label>
                                                    </div>
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
<script src="{{ URL::asset('admin_assets/assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/main/js/plugins-init/dropify-init.js') }}"></script>

<style type="text/css">
    .note {
        margin-top: 0px;
        margin-bottom: 0px;
        color: #fff;
        padding: 0.375rem 0.75rem;
        width: 100%;
        height: 85px;
    }
</style>

@endsection

