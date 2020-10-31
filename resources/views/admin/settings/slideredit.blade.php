@extends('admin.layouts.sidebar')
@section('content')

        <!--**********************************
            Content body start
        ***********************************-->
        

        <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('app.Update Prime Benefit slider')}}</a></li>
        </ol>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        &nbsp;
    </div>
</div>
<!-- row -->
<div class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="content-heading mt-2">{{trans('app.Update Prime Benefit slider')}}</h3>
        </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="form-validation">
                                    <form class="form-valide" action="{{action('Admin\SettingsController@sliderupdate',$id)}}" method="post"  enctype="multipart/form-data" onSubmit="return validatesliderdata()" >
                                        @csrf
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">{{trans('app.Slider Title')}} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="val-title" name="val-title" placeholder="{{trans('app.Give a slider title')}}" value="{{$data['title']}}" maxlength="30">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9" id="titleerr" style="color: #fd397a;">
                                                         @if ($errors->has('val-title'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('val-title') }}</strong>
                                                        </span>
                                                    @endif
                                                    </div>
                                                 </div>


                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label" >{{trans('app.Slider Description')}} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">

                                                        <textarea name="val-descrip" id="val-descrip" maxlength="250" class="note form-control">{{$data['description']}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9" id="descriperr" style="color: #fd397a;">
                                                    @if ($errors->has('val-descrip'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('val-descrip') }}</strong>
                                                        </span>
                                                    @endif
                                                    </div>
                                                 </div>

                                                 <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">{{trans('app.Slider Image')}} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-sm-2 mb-1 mr-5">
                                                        <input type="file" class="dropify" data-default-file="" name="val-image" id="val-image" />
                                                    </div>
                                                    <div class="col-sm-6 mb-1">
                                                        <?php if ($data['image'] != "") {
    ;
}
?>
                                                        <input type="hidden" id="img" value="1">
                                                        <img src="<?php echo url('/public/img/slider/' . $data['image']); ?>"
                                                            style="max-height: 100px;max-width: 200px;min-height:50px;background-color: #ededed;">
                                                    </div>

                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9">
                                                        @if ($errors->has('val-image'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('val-image') }}</strong>
                                                        </span>
                                                    @endif
                                                    </div>
                                                 </div>

                                                 <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9" id="imageerr" style="color: #fd397a">
                                                    </div>
                                                 </div>

                                                <div class="form-group row">
                                                    <div class="col-lg-9 ml-auto">
                                                        <button type="submit" class="btn btn-primary">{{trans('app.Update')}}</button>
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

        

        <style type="text/css">
            .note{
                margin-top: 0px;
                margin-bottom: 0px;
                color: #fff;
                padding: 0.375rem 0.75rem;
                background-color: black;
                width: 100%;
                height: 89px;
            }
        </style>
   <script src="{{ URL::asset('public/admin_assets/assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ URL::asset('public/admin_assets/main/js/plugins-init/dropify-init.js') }}"></script>

@endsection

