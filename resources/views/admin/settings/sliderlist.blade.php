@php
use Carbon\Carbon;
@endphp
@extends('admin.layouts.sidebar')
@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('app.Sliders')}}</a></li>
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
            <h3 class="content-heading mt-2">{{trans('app.Sliders')}}</h3>
        </div>
        <div class="col-lg-12">
            <div class="card">
                
                <div class="card-body">
                <h4 class="card-title">{{trans('app.Note : Maximum 10 sliders can be added')}}</h4>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group row">
                                <div class="col-lg-8">
                                    &nbsp;
                                </div>
                                <div class="col-lg-4">
                                    {{ Form::open(array('url' => 'admin/subscriptions/search' , 'method' => 'get')) }}
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control search_filter" placeholder="{{trans('app.Search by title')}}" maxlength="30" name="search" value="<?php if (isset($search)) {
                                                                                                                                                                        echo $search;
                                                                                                                                                                    } ?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">{{trans('app.Search')}}</button>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table header-border table-responsive-sm">
                            <thead>
                                <tr>
                                    <th>{{ __("S.no")}}</th>
                                    <th>{{trans('app.Title')}}</th>
                                    <th>{{trans('app.Image')}}</th>
                                    <th>{{trans('app.View')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $id = 1; ?>
                                @if(!empty($details))
                                @foreach($details as $det)
                                <tr>
                                    <td>{{$id}}</td>
                                    <td>{{substr($det['title'],0,30)}}</td>
                                    <td>
                                        <img src="{{url('/public/img/slider/'.$det['image'])}}" style="width: auto;height: 100px;">
                                    </td>
                                    <td>
                                        <span>
                                            <a href="{{ URL::to('admin/slidershow/' . $det['id']) }}" class="mr-4" title="{{trans('app.View')}}"><i class="fa fa-eye color-muted"></i> </a>
                                        </span>
                                        <span>
                                            <a href="{{ URL::to('admin/slideredit/' . $det['id']) }}" class="mr-4" title="{{trans('app.Edit')}}"><i class="fa fa-pencil color-muted"></i> </a>
                                        </span>

                                        <span>
                                            <a href="{{ URL::to('admin/sliderdestroy/' . $det['id']) }}" data-title="{{trans('app.Confirmation')}}" class="del_activity_btn mr-4" title="{{trans('app.Delete')}}"><i class="fa fa-trash color-muted"></i> </a>
                                        </span>

                                    </td>
                                </tr>
                                <?php $id++; ?>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6">{{trans('app.No data available in table')}}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection