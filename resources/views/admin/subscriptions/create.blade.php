@extends('admin.layouts.sidebar') @section('content')
<!--**********************************
            Content body start
        ***********************************-->
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/subscriptions/create') }}">{{trans('app.New Subscription Packages')}}</a></li>
        </ol>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
</div>
<!-- row -->
<div class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="content-heading mt-2">{{trans('app.New Subscription Packages')}}</h3>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="form-validation">
                        <form class="form-valide" action="{{ action('Admin\SubscriptionsController@store') }}" method="post" onSubmit="return validatesubdata()" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{trans('app.Title')}} <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" id="val-title" name="val-title" placeholder="{{trans('app.Provide Subscription title')}}" maxlength="30" value="{{ old('val-title') }}" />
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
                                            <input type="text" class="form-control" id="val-gems" name="val-gems" placeholder="{{trans('app.Provide Gems count')}}" maxlength="6" value="{{ old('val-gems') }}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" />
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
                                        <label class="col-lg-3 col-form-label">{{trans('app.Price')}} <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control numberonly" id="val-price" name="val-price" placeholder="{{trans('app.Provide Subscription price')}}" maxlength="6" value="{{ old('val-price') }}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" />
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
                                                    <input type="radio" class="form-check-input" id="val-platform" name="val-platform" value="android" checked>
                                                    {{trans('app.Android')}}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" id="val-platform" name="val-platform" value="ios">
                                                    {{trans('app.iOS')}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{trans('app.Validity')}} <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-8">
                                            <!-- <input
                                                                type="text"
                                                                class="form-control"
                                                                id="val-validity"
                                                                name="val-validity"
                                                                placeholder="Provide Subscription validity.."
                                                                maxlength="20"
                                                                value="{{ old('val-validity') }}"
                                                            /> -->
                                            <select name="val-validity" id="val-validity" class="custom-select mr-sm-2" id="inlineFormCustomSelect">
                                                <option value="">{{trans('app.Choose')}}</option>
                                                <option value="1M">1 month</option>
                                                <option value="3M">3 months</option>
                                                <option value="6M">6 months</option>
                                                <option value="1Y">1 year</option>
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
        <!--  <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Guidelines</h4>
                    <p class="text-muted"><code></code>
                    </p>
                    <div id="accordion-one" class="accordion">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="fa" aria-hidden="true"></i>Sign-in with your Google account</h5>
                            </div>
                            <div id="collapseOne" class="collapse" data-parent="#accordion-one">
                                <div class="card-body">Publish your apps and games with the Google Play Console and grow your business on Google Play. Benefit from features that help you improve your app’s quality, engage your audience, earn revenue, and more.</div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><i class="fa" aria-hidden="true"></i> Google Play Store</h5>
                            </div>
                            <div id="collapseTwo" class="collapse" data-parent="#accordion-one">
                                <div class="card-body">When you publish on Google Play, you put your apps and games in front of people using the billions of active Android devices, in more than 190 countries and territories around the world.</div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree"><i class="fa" aria-hidden="true"></i>Google Play Billing</h5>
                            </div>
                            <div id="collapseThree" class="collapse" data-parent="#accordion-one">
                                <div class="card-body">Sell digital content in your apps, whether in-app products or subscriptions. Google Play handles checkout details so you never have to directly process any financial transactions, and your customers experience a consistent and familiar purchase flow.</div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour"><i class="fa" aria-hidden="true"></i>Google Play services</h5>
                            </div>
                            <div id="collapseFour" class="collapse" data-parent="#accordion-one">
                                <div class="card-body">Take advantage of the latest Google technologies through a single set of APIs, delivered across Android devices worldwide as part of Google Play services.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="col-lg-4">
            <style>
                .isfix {
                    padding: initial;
                    font-size: 1rem;
                    text-align: initial;
                    color: #fff;
                    line-height: 1.2;
                    display: block;
                }

                .isfix:hover {
                    color: #fff;
                }
            </style>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{trans('app.Guidelines')}}</h4>
                    <p class="text-muted"><code></code>
                    </p>
                    <div id="accordion-one">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-0 collapsed btn isfix" data-toggle="collapse" data-target="#oner">
                                    <i class="fa" aria-hidden="true"></i>{{trans('app.Sign-in with your Google account')}}
                                </h5>
                            </div>
                            <div id="oner" class="collapse">
                                <div class="card-body">{{trans('app.Publish your apps and games with the Google Play Console and grow your business on Google Play. Benefit from features that help you improve your app’s quality, engage your audience, earn revenue, and more.')}}</div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-0 collapsed btn isfix" data-toggle="collapse" data-target="#twor"><i class="fa" aria-hidden="true"></i> {{trans('app.Google PlayStore')}}</h5>
                            </div>
                            <div id="twor" class="collapse">
                                <div class="card-body">{{trans('app.When you publish on Google Play, you put your appsand games in front of people using the billions of active Androiddevices, in more than 190 countries and territories around the world.')}}
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-0 collapsed btn isfix" data-toggle="collapse" data-target="#threer"><i class="fa" aria-hidden="true"></i>{{trans('app.Google Play Billing')}}</h5>
                            </div>
                            <div id="threer" class="collapse">
                                <div class="card-body">{{trans('app.Sell digital content in your apps, whether in-app products or subscriptions. Google Play handles checkout details so you never have to directly process any financial transactions, and your customers experience a consistent and familiar purchase flow.')}}</div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-0 collapsed btn isfix" data-toggle="collapse" data-target="#fourr"><i class="fa" aria-hidden="true"></i>{{trans('app.Google Play services')}}</h5>
                            </div>
                            <div id="fourr" class="collapse">
                                <div class="card-body">{{trans('app.Take advantage of the latest Google technologies through a single set of APIs, delivered across Android devices worldwide as part of Google Play services.')}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ URL::asset('public/admin_assets/assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ URL::asset('public/admin_assets/main/js/plugins-init/dropify-init.js') }}"></script>
@endsection
