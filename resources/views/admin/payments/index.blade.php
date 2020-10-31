@extends('admin.layouts.sidebar')
@section('content')
@php use App\Models\Accounts; @endphp<?php
use App\Classes\MyClass;
$myClass = new MyClass();
$settings = $myClass->site_settings();
?>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/payments') }}">{{trans('app.Payments')}}</a>
            </li>
        </ol>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
</div>
<!-- row -->
<div class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="content-heading mt-2">{{trans('app.Payment Settings')}}</h3>
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
                                    {{ Form::open(array('url' => 'admin/payments/search', 'method' => 'get')) }}
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control search_filter"
                                            placeholder="{{trans('app.Search by user')}}" name="search"
                                            autocomplete="off" value="@php if (isset($search)) echo $search; @endphp"
                                            maxlength="30">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary"
                                                type="submit">{{trans('app.Search')}}</button>
                                            <input type="hidden" name="sort"
                                                value="<?php if (isset($sortby)) {echo $sortby;} else {echo 'pymt_on';}?>">
                                            <input type="hidden" name="direction"
                                                value="<?php if (isset($sortorder)) {echo $sortorder;} else {echo 'desc';}?>">
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
                                    <th scope="col">{{trans('app.User')}}</th>
                                    <th scope="col">{{trans('app.Paid For')}}</th>
                                    <th scope="col">{{trans('app.Transcation ID')}}</th>
                                    <th scope="col">{{trans('app.Paid Amount')}}</th>
                                    <th scope="col">@sortablelink('pymt_on',trans('app.Paid On'))</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $id =1; @endphp
                                @if(!empty($transcations))
                                @foreach($transcations as $trans)
                                @php $name = Accounts::where('_id', $trans['user_id'])->first(); @endphp
                                <tr>
                                    <td>{{$id}}</td>
                                    <td>{{$name['acct_name']}}</td>
                                    <td>{{$trans['pymt_type']}}</td>
                                    <td>{{$trans['pymt_transid']}}</td>
                                    <td>{{$trans['pymt_amt']}}</td>
                                    <td><span class="text-muted">
                                            <?php
                                        try {
                                        $date = new DateTime($trans['pymt_on']->toDateTime()->format('M d, H:i a'), new DateTimeZone('UTC'));
                                        $date->setTimezone(new DateTimeZone($settings->timezone));
                                        echo $date->format('M d, h:i a');
                                        } catch (\Throwable $th) {
                                        echo $trans['pymt_on']->toDateTime()->format('M d, H:i a');
                                        }
                                        ?>
                                        </span></td>
                                </tr>
                                @php $id++; @endphp
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5">{{trans('app.No data found.')}}</td>
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