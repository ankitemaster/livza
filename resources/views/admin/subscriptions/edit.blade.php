@extends('admin.layouts.sidebar')
@section('content')

<!--**********************************
            Content body start
        ***********************************-->

        <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
                            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/subscriptions/edit/'.$id) }}">{{trans('app.Edit Subscription Package')}}</a></li>
                        </ol>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
                    
                    </div>
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <h3 class="content-heading" style="margin-bottom: 0px;margin-top: 0px;">{{trans('app.Edit Subscription Package')}}</h3> 
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <a class="btn btn-primary mb-2" href="{{ URL::previous() }}">{{trans('app.Back')}}</a>
                    </ol>
                </div>
            </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <div class="form-validation">
                            <form class="form-valide" action="{{action('Admin\SubscriptionsController@update',$id)}}" method="post" onSubmit="return validatesubdata()" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-12">

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">{{trans('app.Title')}} <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" id="val-title" name="val-title" placeholder="{{trans('app.Provide Subscription title')}}" maxlength="30" value="{{$sub->subs_title}}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-9">

                                                <span class="help-block text-danger" id="titleerr">
                                                    @if ($errors->has('val-title'))
                                                    <strong>{{ $errors->first('val-title') }}</strong>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">{{trans('app.No of Gems')}} <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control numberonly" id="val-gems" name="val-gems" placeholder="{{trans('app.Provide Gems count')}}" maxlength="6" value="{{$sub->subs_gems}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-9">

                                                <span class="help-block text-danger" id="gemserr">
                                                    @if ($errors->has('val-gems'))
                                                    <strong>{{ $errors->first('val-gems') }}</strong>
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
                                                    <input type="radio" class="form-check-input" id="val-platform" name="val-platform" value="android" <?php if ($sub->platform == 'android') {echo 'checked=checked';}?>>
                                                    {{trans('app.Android')}}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" id="val-platform" name="val-platform" value="ios" <?php if ($sub->platform == 'ios') {echo 'checked=checked';}?>>
                                                    {{trans('app.iOS')}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>


                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">{{trans('app.Price')}} <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control numberonly" id="val-price" name="val-price" placeholder="{{trans('app.Provide Subscription price')}}" maxlength="6" value="{{$sub->subs_price}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
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
                                            <label class="col-lg-3 col-form-label">{{trans('app.Validity')}} <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <select name="val-validity" id="val-validity" class="custom-select mr-sm-2" id="inlineFormCustomSelect">
                                                    <option value="">{{trans('app.Choose')}}</option>
                                                    <option value="1M" @if ($sub->subs_validity=="1M")
                                                        selected="selected"
                                                        @endif>1 month</option>
                                                    <option value="3M" @if ($sub->subs_validity=="3M")
                                                        selected="selected"
                                                        @endif>3 months</option>
                                                    <option value="6M" @if ($sub->subs_validity=="6M")
                                                        selected="selected"
                                                        @endif>6 months</option>
                                                    <option value="1Y" @if ($sub->subs_validity=="1Y")
                                                        selected="selected"
                                                        @endif>1 year</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-9">

                                                <span class="help-block text-danger" id="validityerr">
                                                    @if ($errors->has('val-validity'))
                                                    <strong>{{ $errors->first('val-validity') }}</strong>
                                                    @endif
                                                </span>

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

    <script>

// function validatesubdata() {
//     var title = $("#val-title").val();
//     var gems = $("#val-gems").val();
//     var price = $("#val-price").val();
//     var validity = $("#val-validity").val();

  
//     var a = confirm(
//         "<?php echo trans('app.Please recheck your data. you dont have delete option.'); ?>"
//     );
//     if (a == true) {
//         return true;
//     } else {
//         return false;
//     }
// }

</script>

    <script src="{{ URL::asset('public/admin_assets/assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ URL::asset('public/admin_assets/main/js/plugins-init/dropify-init.js') }}"></script>

@endsection