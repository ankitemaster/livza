@extends('admin.layouts.sidebar')
@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('app.Gem Packages')}}</a></li>
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
            <h3 class="content-heading mt-2">{{trans('app.Gem Packages')}}</h3>
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
                                {{ Form::open(array('url' => 'admin/gems/search' , 'method' => 'get')) }}
                                    <div class="input-group mb-3">
                                    <input type="text" class="form-control search_filter" placeholder="{{trans('app.search by package name')}}" name="search" value="<?php if (isset($search)) {echo $search;}?>">
                                        <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">{{trans('app.Search')}}</button>
                                        <input type="hidden" name="sort" value="<?php if (isset($sortby)) {echo $sortby;} else {echo 'created_at';}?>">
                                        <input type="hidden" name="direction" value="<?php if (isset($sortorder)) {echo $sortorder;} else {echo 'desc';}?>">
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
                                    <th scope="col">@sortablelink('gem_title',trans('app.Package name'))</th>
                                    <th scope="col">{{trans('app.Icon')}}</th>
                                    <th scope="col">@sortablelink('gem_count',trans('app.Gems'))</th>
                                    <th scope="col">@sortablelink('gem_price',trans('app.Price'))</th>
                                    <th scope="col">@sortablelink('purchase_count',trans('app.No of Usage'))</th>
                                    <th scope="col">{{trans('app.View')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $id = 1;?>
                                @if(!empty($gems))
                                @foreach($gems as $gem)
                                <tr>
                                                    <td>{{$id}}</td>
                                                    <td><?php echo ucfirst($gem['gem_title']);if ($gem['platform'] == "ios") {echo "  <i class='fa fa-apple'></i>";} else {echo "  <i class='fa fa-android'></i>";} ?></td>
                                                    <td>
                                                        <img src="{{url('/public/img/gems/'.$gem['gem_icon'])}}" height="30" width="auto">
                                                    </td>
                                                    <td>{{$gem['gem_count']}}</td>
                                                    <td>{{$gem['gem_price']}}</td>
                                                    <td>@if(isset($gem['purchase_count']) && $gem['purchase_count']) {{$gem['purchase_count']}} @else {{0}} @endif</td>
                                                    <td>
                                                        <span>
                                                            <a href="{{ URL::to('admin/gems/edit/' . $gem['_id']) }}" class="mr-4"  title="{{trans('app.Edit')}}"><i class="fa fa-pencil color-muted"></i> </a>
                                                        </span>
                                                         <span>
                                                            <a href="{{ URL::to('admin/gems/destroy/' . $gem['_id']) }}" class="del_activity_btn mr-4"  data-title="Confirm" title="{{trans('app.Delete')}}"><i class="fa fa-trash color-muted"></i> </a>
                                                        </span>
                                                    </td>
                                                </tr>
                                <?php $id++;?>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5">{{trans('app.No data available in table')}}</td>
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