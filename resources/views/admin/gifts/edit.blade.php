@extends('admin.layouts.sidebar')
@section('content')

        <!--**********************************
            Content body start
        ***********************************-->
        

        <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('app.Update Gift')}}</a></li>
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
            <h3 class="content-heading mt-2">{{trans('app.Update Gift')}}</h3>
        </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                               
                                <div class="form-validation">
                                    <form class="form-valide" action="{{action('Admin\GiftsController@update',$id)}}" method="post" onSubmit="return validategiftdata()" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-xl-12">

                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">{{trans('app.Gift Title')}} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="val-gifttitle" name="val-gifttitle" placeholder="{{trans('app.Provide Gift title')}}" maxlength="30" value="{{$gift->gft_title}}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9">
                                                        
                                                    <span class="help-block text-danger" id="gifttitleerr">
                                                        @if ($errors->has('val-gifttitle'))
                                                            <strong>{{ $errors->first('val-gifttitle') }}</strong>
                                                            @endif
                                                        </span>
                                                    </div>
                                                 </div>

                                                 <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">{{trans('app.Gems Count for prime account')}} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control numberonly" id="val-primegemscount" name="val-primegemscount" placeholder="{{trans('app.Provide Gems count')}}" maxlength="6" value="{{$gift->gft_gems_prime}}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9">
                                                        
                                                    <span class="help-block text-danger" id="primegemscounterr">
                                                        @if ($errors->has('val-primegemscount'))
                                                            <strong>{{ $errors->first('val-primegemscount') }}</strong>
                                                            @endif
                                                        </span>
                                                    </div>
                                                 </div>

                                                 <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">{{trans('app.Gems Count for non-prime account')}} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control numberonly" id="val-gemscount" name="val-gemscount" placeholder="{{trans('app.Provide Gems count')}}" maxlength="6" value="{{$gift->gft_gems}}">
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
                                                    <label class="col-lg-3 col-form-label">{{trans('app.Gift icon')}} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-sm-2 mb-1 mr-5">
                                                        <input type="file" class="dropify" data-default-file="" name="val-icon" id="val-icon" />
                                                    </div>
                                                    <div class="col-sm-6 mb-1">
                                                        <?php if($gift->gft_icon != "") ?>
                                                        <input type="hidden" id="img" value="1">
                                                        <img src="<?php echo url('/public/img/gifts/'.$gift->gft_icon); ?>"
                                                            style="background-color: #ededed;max-height: 100px;max-width: 200px">
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
            
        

            <script src="{{ URL::asset('public/admin_assets/assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ URL::asset('public/admin_assets/main/js/plugins-init/dropify-init.js') }}"></script>
        
@endsection

