@php
use Carbon\Carbon; 
use App\Classes\MyClass;
$myClass = new MyClass();
$settings = $myClass->site_settings();
@endphp
@extends('admin.layouts.sidebar')
@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('app.Gift Packs')}}</a></li>
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
            <h3 class="content-heading mt-2">{{trans('app.Gift Packs')}}</h3>
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
                                {{ Form::open(array('url' => 'admin/gifts/search' , 'method' => 'get')) }}
                                    <div class="input-group mb-3">
                                    <input type="text" class="form-control search_filter" placeholder="{{trans('app.Search by gift title')}}" name="search" value="<?php if(isset($search)){ echo $search; } ?>">
                                        <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">{{trans('app.Search')}}</button>
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
                                    <th scope="col">@sortablelink('gft_title',trans('app.Gift title'))</th>
                                    <th scope="col">{{trans('app.Home')}}{{ __("Icon")}}</th>
                                    <th scope="col">@sortablelink('gft_gems_prime',trans('app.prime gems'))</th>
                                    <th scope="col">@sortablelink('gft_gems',trans('app.Non-prime gems'))</th>
                                    <th scope="col">{{trans('app.Home')}}{{ __("View")}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $id = 1; ?>
                                @if(!empty($gifts))
                                @foreach($gifts as $gift)
                                <tr>

                                                    <td>{{$id}}</td>
                                                    <td><?php echo ucfirst($gift['gft_title']);  ?></td>
                                                    <td>
                                                        <img src="{{url('/public/img/gifts/'.$gift['gft_icon'])}}" style="width: auto;height: 60px;">
                                                    </td>
                                                    <td><img src="{{url('/public/img/gems.png')}}" style="width: 20px;height: 18px;"> {{$gift['gft_gems_prime']}}</td>
                                                    <td><img src="{{url('/public/img/gems.png')}}" style="width: 20px;height: 18px;"> {{$gift['gft_gems']}}</td>
                                                        
                                                    <td>
                                                       
                                                        <span>
                                                            <a href="{{ URL::to('admin/gifts/edit/' . $gift['_id']) }}" class="mr-4"  title="{{trans('app.Edit')}}"><i class="fa fa-pencil color-muted"></i> </a>
                                                        </span>

                                                         <span>
                                                            <a href="{{ URL::to('admin/gifts/destroy/' . $gift['_id']) }}" class="del_activity_btn mr-4"  data-title="{{trans('app.Confirm')}}" title="{{trans('app.Delete')}}"><i class="fa fa-trash color-muted"></i> </a>
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
                        <div class="pagination-wrapper"> {!! $pagination->render() !!} </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection