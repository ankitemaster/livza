@extends('admin.layouts.sidebar')
@section('content')

        <!--**********************************
            Content body start
        ***********************************-->
        

                    <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
                            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/helps/create') }}">{{trans('app.New Help page')}}</a></li>
                        </ol>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
                    
                    </div>
                            <!-- row -->

                            <div class="container">
                            <div class="row">
                            <div class="col-12">
                            <h3 class="content-heading mt-2">{{trans('app.New Help page')}}</h3>
                        </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                               
                                <div class="form-validation">
                                    <form class="form-valide" action="{{action('Admin\HelpsController@store')}}" method="post" onSubmit="return validatehelpdata()">
                                        @csrf
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">{{trans('app.Title')}} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="val-title" name="val-title" placeholder="{{trans('app.Give a title')}}" maxlength="30" list="queries" value="{{old('val-title')}}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9" id="titerr" style="color: #fd397a;">
                                                         @if ($errors->has('val-title'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('val-title') }}</strong>
                                                        </span>
                                                    @endif
                                                    </div>
                                                 </div>


                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">{{trans('app.Description')}} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <!-- <textarea class="form-control" rows="4" id="comment val-descrip" name="val-descrip" placeholder="And description.."></textarea> -->
                                                        <textarea name="summernoteInput" class="" id="summernoteInput" rows="4" cols="50" placeholder="Give a description..">{{old('summernoteInput')}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9" id="titerr" style="color: #fd397a;">
                                                         @if ($errors->has('summernoteInput'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('summernoteInput') }}</strong>
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
            
        

        <!-- <script>
        $(document).ready(function() {
            $('.summernote').summernote();
            $('.note-table').hide(); 
            $('.note-style').hide(); 
        });
        
</script> -->
<script>
        CKEDITOR.replace( 'summernoteInput' );
    </script>
 <script src="{{ URL::asset('/vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
 <script src="{{ URL::asset('/vendor/unisharp/laravel-ckeditor/adapters/jquery.js')}}"></script>
 <script>
        $('textarea').ckeditor();
</script>
<style>
#cke_30{
    display:none;
}
</style>
@endsection

