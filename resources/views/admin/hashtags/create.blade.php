@extends('admin.layouts.sidebar')
@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/gems/create') }}">{{trans('app.New Hashtag')}}</a></li>
        </ol>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
</div>
<!-- row -->
<div class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="content-heading mt-2">{{trans('app.New Hashtag')}}</h3>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-validation">
                        <form class="form-valide" action="{{action('Admin\HashtagsController@store')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{trans('app.Topic')}} <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="hashtag-topic" placeholder="{{trans('app.Topic')}}" maxlength="30" value="{{old('hashtag-topic')}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            <span class="help-block text-danger">
                                                @if ($errors->has('hashtag-topic'))
                                                <strong>{{ $errors->first('hashtag-topic') }}</strong>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9"><p class="mb-1 text-muted">{{trans("app.Note: Topic can't be only numeric, it must have at least one alphanumeric character or the underscore symbol, it should not any special characters & in between spaces are also not allowed.")}}</p></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-9 ml-auto">
                                            <button type="submit" class="btn btn-primary" id="savee">{{trans('app.Save')}}</button>
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