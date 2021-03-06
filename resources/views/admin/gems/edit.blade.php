@extends('admin.layouts.sidebar')
@section('content')

<!--**********************************
            Content body start
        ***********************************-->


<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('app.Update Gem Package')}}</a></li>
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
            <h3 class="content-heading mt-2">{{trans('app.Update Gem Package')}}</h3>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <div class="form-validation">
                        <form class="form-valide" action="{{action('Admin\GemsController@update',$id)}}" method="post" onSubmit="return validategemdata()" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-xl-12">

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{trans('app.Gems Package name')}} <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" id="val-gemstitle" name="val-gemstitle" placeholder="{{trans('app.Provide Gems Package name')}}" maxlength="50" value="{{$gem->gem_title}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">

                                            <span class="help-block text-danger" id="gemstitleerr">
                                                @if ($errors->has('val-gemstitle'))
                                                <strong>{{ $errors->first('val-gemstitle') }}</strong>
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{trans('app.Gems Count')}} <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" id="val-gemscount" name="val-gemscount" placeholder="{{trans('app.Provide Gems count')}}" maxlength="10" value="{{$gem->gem_count}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">

                                            <span class="help-block text-danger" id="gemscounterr">
                                                @if ($errors->has('val-gemscount'))
                                                <strong>{{ $errors->first('val-gemscount') }}</strong>
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{trans('app.Gem Package Price')}} <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" id="val-price" name="val-price" placeholder="{{trans('app.Provide Gems Package price')}}" maxlength="10" value="{{$gem->gem_price}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">

                                            <span class="help-block text-danger" id="priceerr">
                                                @if ($errors->has('val-price'))
                                                <strong>{{ $errors->first('val-price') }}</strong>
                                                @endif
                                            </span>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{trans('Platform')}} <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-8">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" id="val-platform" name="val-platform" value="android" <?php if ($gem->platform == 'android') {echo 'checked=checked';}?>>
                                                    {{trans('app.Android')}}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" id="val-platform" name="val-platform" value="ios" <?php if ($gem->platform == 'ios') {echo 'checked=checked';}?>>
                                                    {{trans('app.iOS')}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{trans('app.Gem Package icon')}} <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-2 mb-1 mr-5">
                                            <input type="file" class="dropify" data-default-file="" name="val-icon" id="val-icon" />
                                        </div>
                                        <div class="col-sm-6 mb-1">
                                            <?php if ($gem->gem_icon != "") {
    ;
}
?>
                                            <input type="hidden" id="img" value="1">
                                            <img src="<?php echo url('/public/img/gems/' . $gem->gem_icon); ?>" style="background-color: #ededed;max-height: 100px;max-width: 200px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                        @if ($errors->has('val-icon'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('val-icon') }}</strong>
                                                        </span>
                                                    @endif
                                            <span class="help-block text-danger" id="iconerr">

                                            </span>
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



<script src="{{ URL::asset('public/admin_assets/assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ URL::asset('public/admin_assets/main/js/plugins-init/dropify-init.js') }}"></script>



@endsection