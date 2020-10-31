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
            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/socialsettings') }}">{{trans('app.Social Links Settings')}}</a></li>
        </ol>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
       
    </div>
            <!-- row -->

            <div class="container">
            <div class="row">
            <div class="col-12">
            <h3 class="content-heading mt-2">{{trans('app.Social Links Settings')}}</h3>
        </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                               
                                <div class="form-validation">
                                    <form class="form-valide" action="{{action('Admin\SettingsController@socialsettingsupdate')}}" method="post" enctype="multipart/form-data" onSubmit="return validatedata()">
                                        @csrf
                                        <div class="row">
                                            <div class="col-xl-12">
                                            <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text fb"><i class="fa fa-android" aria-hidden="true"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control fb-link" id="val-androidlink" name="val-androidlink" value="{{$details->androidlink}}" placeholder="{{trans('app.Provide android link')}}" maxlength="90">
                                                            
                                                        </div>

                                                        
                                                    </div>
                                                    <div class="col-lg-3"></div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9">
                                                    </div>
                                                 </div>

                                                 <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text fb"><i class="fa fa-apple" aria-hidden="true"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control fb-link" id="val-ioslink" name="val-ioslink" value="{{$details->ioslink}}" placeholder="{{trans('app.Provide IOS link')}}" maxlength="90">
                                                            
                                                        </div>

                                                        
                                                    </div>
                                                    <div class="col-lg-3"></div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9">
                                                    </div>
                                                 </div>

                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text fb"><i class="fa fa-facebook" aria-hidden="true"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control fb-link" id="val-facebooklink" name="val-facebooklink" value="{{$details->facebooklink}}" placeholder="{{trans('app.Provide facebook link')}}" maxlength="90">
                                                            
                                                        </div>

                                                        
                                                    </div>
                                                    <div class="col-lg-3"></div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9">
                                                    </div>
                                                 </div>


                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text tw"><i class="fa fa-twitter" aria-hidden="true"></i></span>
                                                            </div>
                                                        <input type="text" class="form-control tw-link" id="val-twitterlink" name="val-twitterlink" value="{{$details->twitterlink}}" placeholder="{{trans('app.Provide twitter link')}}" maxlength="90">
                                                    </div>
                                                    </div>
                                                    <div class="col-lg-3"></div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9">
                                                    </div>
                                                 </div>

                                                 <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text insta"><i class="fa fa-instagram" aria-hidden="true"></i></span>
                                                            </div>
                                                        <input type="text" class="form-control insta-link" id="val-instagramlink" name="val-instagramlink" value="{{$details->instagramlink}}" placeholder="{{trans('app.Provide instagram link')}}" maxlength="90">
                                                    </div>
                                                    </div>
                                                    <div class="col-lg-3"></div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9">
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

