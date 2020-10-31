@extends('admin.layouts.sidebar')
@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('app.Hashtags')}}</a></li>
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
            <h3 class="content-heading mt-2">{{trans('app.Hashtags')}}</h3>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <a href="{{ URL::to('admin/hashtags/create')}}"><button type="button" class="btn btn-rounded btn-primary"><span class="btn-icon-left text-info"><i class="fa fa-plus color-info"></i> </span>{{trans('app.New Hashtag')}}</button></a>
                                </div>
                                <div class="col-lg-4">
                                    &nbsp;
                                </div>
                                <div class="col-lg-4">
                                    {{ Form::open(array('url' => 'admin/hashtags/search','method' => 'get')) }}
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control search_filter" placeholder="{{trans('app.Search hashtags')}}" name="search" value="<?php if(isset($search)){ echo $search; } ?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">{{trans('app.Search')}}</button>
                                            <input type="hidden" name="sort" value="<?php if(isset($sortby)){echo $sortby; }else{ echo 'created_at'; } ?>">
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
                                    <th scope="col">@sortablelink('topic',trans('app.Topic'))</th>
                                    <th scope="col">{{ __("View")}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $id = 1; ?>
                                @if(!empty($hashtags))
                                @foreach($hashtags as $eachhashtag)
                                <tr>
                                    <td>{{$id}}</td>
                                    <td><?php echo ucfirst($eachhashtag['topic']);  ?></td>
                                    <td>
                                        <span>
                                            <a href="{{ URL::to('admin/hashtags/destroy/' . $eachhashtag['_id']) }}" class="del_activity_btn mr-4"  data-title="{{trans('app.Confirm')}}" title="{{trans('app.Delete')}}"><i class="fa fa-trash color-muted"></i> </a>
                                        </span>
                                    </td>
                                </tr>
                                <?php $id++; ?>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="3">{{trans('app.No data available in table')}}</td>
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