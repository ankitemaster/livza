@php
use Carbon\Carbon;
use App\Models\Accounts;
use App\Models\Streams;
@endphp
@extends('admin.layouts.sidebar')
@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('app.Stream Reports')}}</a></li>
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
            <h3 class="content-heading mt-2">{{trans('app.Stream Reports')}}</h3>
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
                                    {{ Form::open(array('url' => 'admin/reports/reportsearch', 'method' => 'get')) }}
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control search_filter" maxlength="30" placeholder="{{trans('app.Search by stream title')}}" name="search" value="<?php if (isset($search)) {
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
                                    <th scope="col">{{ __("S.no")}}</th>
                                    <th scope="col">{{trans('app.Stream Title')}}</th>
                                    <th scope="col">{{trans('app.Reports count')}}</th>
                                    <th scope="col">{{trans('app.View')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $id = 1; ?>
                                @if(!empty($reports))
                                @foreach($reports as $report)
                                @php
                                $uidd = $report->stream_id;
                                $stream = Streams::find($uidd);
                                $idd = new \MongoDB\BSON\ObjectID($report->stream_id);
                                $offset = (string)$idd;
                                @endphp
                                
                                <tr>
                                    <td>{{$id}}</td>
                                    <td><?php if($stream['title'] != ''){if (strlen($stream['title']) > 30) {
                                            echo substr($stream['title'], 0, 20);
                                            echo "...";
                                        } else {
                                            echo $stream['title'];
                                        }} ?>
                                    </td>
                                    <td><?php echo $reportcounts[$offset]; ?>
                                    </td>
                                    <td>
                                        <span>
                                            <a href="{{ URL::to('admin/reports/show/' . $uidd) }}" class="mr-4" title="{{trans('app.View')}}"><i class="fa fa-eye color-muted"></i> </a>
                                        </span>
                                        <span>
                                            <a href="{{ URL::to('admin/reports/reportdestroy/' . $uidd) }}" class="del_activity_btn mr-4" data-title="{{trans('app.Confirmation')}}" title="{{trans('app.Delete')}}"><i class="fa fa-trash color-muted"></i> </a>
                                        </span>
                                    </td>
                                </tr>
                                <?php $id++; ?>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4">{{trans('app.No data available in table')}}</td>
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