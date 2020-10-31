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
            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/logosettings') }}">{{trans('app.Media Settings')}}</a></li>
        </ol>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
       
    </div>
            <!-- row -->

            <div class="container">
            <div class="row">
            <div class="col-12">
            <h3 class="content-heading mt-2">{{trans('app.Media Settings')}}</h3>
        </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="form-validation">
                                    <form class="form-valide" action="{{action('Admin\SettingsController@logosettingsupdate')}}" method="post" enctype="multipart/form-data" onSubmit="return validatedata()">
                                        @csrf
                                        <div class="row">
                                            <div class="col-xl-12">
                                               <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">{{trans('app.Logo')}} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-sm-2 mb-1 mr-5">
                                                        <input type="file" class="dropify" data-default-file="" name="val-logo" id="val-logo" />
                                                    </div>
                                                    <div class="col-sm-6 mb-1">
                                                        <?php if ($details->logo != "") {
    ;
}
?>
                                                        <img src="<?php echo url('/public/img/logo/' . $details->logo); ?>"
                                                            style="background-color: #ededed;max-height: 100px;max-width: 200px">
                                                    </div>

                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9">
                                                        @if ($errors->has('val-logo'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('val-logo') }}</strong>
                                                        </span>
                                                    @endif
                                                    <p class="card-text text-dark text-dander">{{trans('app.Logo image size must be less than 2mb')}} </p>
                                                    </div>
                                                 </div>

                                                 <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9" id="logoerr" style="color: #fd397a">
                                                    </div>
                                                 </div>


                                                 <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">{{trans('app.Dark Logo')}} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-sm-2 mb-1 mr-5">
                                                        <input type="file" class="dropify" data-default-file="" name="val-darklogo" id="val-darklogo" />
                                                    </div>
                                                    <div class="col-sm-6 mb-1">
                                                        <?php if ($details->darklogo != "") {
    ;
}
?>
                                                        <img src="<?php echo url('/public/img/logo/' . $details->darklogo); ?>"
                                                            style="background-color: #ededed;max-height: 100px;max-width: 200px">
                                                    </div>

                                                </div>
                                                 <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9">
                                                        @if ($errors->has('val-darklogo'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('val-darklogo') }}</strong>
                                                        </span>
                                                    @endif
                                                    <p class="card-text text-dark text-dander">{{trans('app.Dark logo image size must be less than 2mb')}} </p>
                                                    </div>
                                                 </div>

                                                 <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9" id="darklogoerr" style="color: #fd397a">
                                                    </div>
                                                 </div>


                                                 <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">{{trans('app.Favicon')}} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-sm-2 mb-1 mr-5">
                                                        <input type="file" class="dropify" data-default-file="" name="val-favicon" id="val-favicon" />
                                                    </div>
                                                    <div class="col-sm-6 mb-1">
                                                        <?php if ($details->favicon != "") {
    ;
}
?>
                                                        <img src="<?php echo url('/public/img/logo/' . $details->favicon); ?>"
                                                            style="max-height: 100px;max-width: 200px;min-height:50px;background-color: #ededed;">
                                                    </div>

                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9">
                                                        @if ($errors->has('val-favicon'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('val-favicon') }}</strong>
                                                        </span>
                                                    @endif
                                                    <p class="card-text text-dark">{{trans('app.Favicon size image must be less than 2mb')}} </p>
                                                    </div>
                                                 </div>

                                                 <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9" id="faviconerr" style="color: #fd397a">
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

