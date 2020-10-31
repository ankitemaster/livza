@extends('admin.layouts.sidebar')
@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('app.Helps list')}}</a></li>
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
            <h3 class="content-heading mt-2">{{trans('app.Helps')}}</h3>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group row">
                                <div class="col-lg-8">
                                    &nbsp;
                                </div>
                                <div class="col-lg-4">
                                    {{ Form::open(array('url' => 'admin/helps/search' ,'method' => 'get')) }}
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control search_filter" placeholder="{{trans('app.Search by title')}}" name="search" max-length="30" value="<?php if (isset($search)) {
                                                                                                                                                                        echo $search;
                                                                                                                                                                    } ?>">

                                           
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">{{ trans('app.Search')}}</button>
                                            <input type="hidden" name="sort" value="<?php if(isset($sortby)){                                            echo $sortby; }else{ echo 'created_at'; } ?>">
                                        <input type="hidden" name="direction" value="<?php if(isset($sortorder)){ echo $sortorder; }else{ echo 'desc'; } ?>">
                                        
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
                                    <th scope="col">{{ __("S.no")}}</th>
                                    <th scope="col">@sortablelink('help_title',trans('app.Title'))</th>
                                    <th scope="col">{{trans('app.View')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $id = 1; ?>
                                @if(!empty($helps))
                                @foreach($helps as $help)
                                <tr>
                                    <td>{{$id}}</td>
                                    <td>{{$help['help_title']}}</td>

                                    <td>
                                        <span>
                                            <a href="{{ URL::to('admin/helps/show/' . $help['_id']) }}" class="mr-4" title="{{trans('app.View')}}"><i class="fa fa-eye color-muted"></i> </a>
                                        </span>
                                        <span>
                                            <a href="{{ URL::to('admin/helps/edit/' . $help['_id']) }}" class="mr-4" title="{{trans('app.Edit')}}"><i class="fa fa-pencil color-muted"></i> </a>
                                        </span>
                                        @if((isset($help['help_type']) && $help['help_type'] != 1) || !isset($help['help_type']))
                                        <span>
                                            <a href="{{ URL::to('admin/helps/destroy/' . $help['_id']) }}" class="del_activity_btn mr-4" title="{{trans('app.Delete')}}" data-title="{{trans('app.Confirm')}}"><i class="fa fa-trash color-muted"></i> </a>
                                        </span>
                                        @endif
                                    </td>

                                </tr>
                                <?php $id++; ?>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="7">{{trans('app.No data available in table')}}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="pagination-wrapper"> {!! $pagination->render() !!} </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection