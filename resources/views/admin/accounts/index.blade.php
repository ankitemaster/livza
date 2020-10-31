@extends('admin.layouts.sidebar')
@section('content')
<?php
use App\Classes\MyClass;
$myClass = new MyClass();
$settings = $myClass->site_settings();
?>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a
                href="{{ URL::to('admin/accounts') }}">{{trans('app.Approved Accounts')}}</a></li>
            </ol>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
    </div>
    <!-- row -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="content-heading mt-2">{{trans('app.Approved Accounts')}}</h3>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12">
                                {{ Form::open(array('url' => 'admin/accounts/search', 'method' => 'get')) }}
                                <div class="form-group row">
                                    <div class="col-lg-2">
                                        <select name="search_for" class="custom-select mr-sm-2" id="inlineFormCustomSelect">
                                            <option value="acct_name" <?php if($search_for === "acct_name") { echo "selected"; } ?>>{{trans('app.Name')}}</option>
                                            <option value="acct_username" <?php if($search_for === "acct_username") { echo "selected"; } ?>>{{trans('app.Username')}}</option>
                                            <option value="acct_phoneno" <?php if($search_for === "acct_phoneno") { echo "selected"; } ?>>{{trans('app.Mobile no')}}</option>
                                            <option value="acct_mailid" <?php if($search_for === "acct_mailid") { echo "selected"; } ?>>{{trans('app.Email')}}</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        &nbsp;
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control search_filter"
                                            placeholder="{{trans('app.Search Accounts')}}" name="search"
                                            autocomplete="off" value="@php if (isset($search)) echo $search; @endphp"
                                            maxlength="30">
                                            <input type="hidden" name="status" value="1">
                                            <input type="hidden" name="sort"
                                            value="<?php if(isset($sortby)){ echo $sortby; }else{ echo 'acct_createdat'; } ?>">
                                            <input type="hidden" name="direction"
                                            value="<?php if(isset($sortorder)){ echo $sortorder; }else{ echo 'desc'; } ?>">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary"
                                                type="submit">{{trans('app.Search')}}</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table header-border table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __("S.no")}}</th>
                                        <th scope="col">@sortablelink('acct_name',trans('app.Name')) </th>
                                        <th scope="col">@sortablelink('acct_username',trans('app.Username')) </th>
                                        <th scope="col">@sortablelink('acct_age',trans('app.age'))</th>
                                        <th scope="col">@sortablelink('acct_gender',trans('app.Gender'))</th>
                                        <th scope="col">@sortablelink('acct_createdat',trans('app.Created on'))</th>
                                        <th scope="col">{{trans('app.Action')}}</th>
                                        <th scope="col">{{trans('app.View')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $id =1; @endphp
                                    @if(!empty($accounts))
                                    @foreach($accounts as $acc)
                                    <tr>
                                        <td>{{$id}}</td>
                                        <td>
                                            @if(isset($acc['acct_membership']) && $acc['acct_membership'] == 'sub')<img
                                            src="{{url('/public/img/crown.png')}}"
                                            style="width: auto;height: 20px;vertical-align: text-bottom;">@endif
                                        {{Str::substr($acc['acct_name'], 0, 17)}}</td>
                                        <td>
                                            @if(isset($acc['acct_membership']) && $acc['acct_membership'] == 'sub')<img src="{{url('/public/img/crown.png')}}" style="width: auto;height: 20px;vertical-align: text-bottom;">@endif {{Str::substr($acc['acct_username'], 0, 17)}}</td>
                                        <td>{{$acc['acct_age']}}</td>
                                        <td>{{$acc['acct_gender']}}</td>
                                        <td><span class="text-muted"><?php

                                        try {
                                            $date = new DateTime($acc['acct_createdat']->toDateTime()->format('M d, H:i a'), new DateTimeZone('UTC'));

                                            $date->setTimezone(new DateTimeZone($settings->timezone));
                                            echo $date->format('M d, h:i a');
                                        } catch (\Throwable $th) {
                                          echo $acc['acct_createdat']->toDateTime()->format('M d, H:i a');
                                      }
                                      ?></span></td>
                                      <td>
                                        @if($acc['acct_status'] ==0)
                                        <a href="{{ URL::to('admin/accounts/changestatus/' . $acc['_id']).'/1' }}"
                                        style="cursor: pointer;"
                                        class="badge mb-2 mb-xl-0 badge-rounded badge-danger action_btn_act">{{trans('app.Active')}}
                                    </a>
                                    @elseif($acc['acct_status'] ==1)
                                    <a href="{{ URL::to('admin/accounts/changestatus/' . $acc['_id']).'/0' }}"
                                    style="cursor: pointer;"
                                    class="badge mb-2 mb-xl-0 badge-rounded badge-dark action_btn_inact">{{trans('app.Inactive')}}
                                </a>
                                @endif
                            </span>
                        </td>
                        <td>
                            <span>
                                <a href="{{ URL::to('admin/accounts/show/' . $acc['_id']) }}" class="mr-4"
                                data-toggle="tooltip" data-placement="right"
                                title="{{trans('app.View')}}"><i class="fa fa-eye color-muted"></i> </a>
                            </span>
                        </td>
                    </tr>
                    @php $id++; @endphp
                    @endforeach
                    @else
                    <tr>
                        <td colspan="8">{{trans('app.No data found.')}}</td>
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