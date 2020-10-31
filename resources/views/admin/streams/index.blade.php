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
                <?php if(isset($pid)){ ?>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
                    <li class="breadcrumb-item active"><a href="{{ URL::to('admin/accounts/show/'.$pid) }}">{{trans('app.User')}}</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('app.Streams')}}</a></li>
                <?php }else{ ?>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
                    <li class="breadcrumb-item active"><a href="{{ URL::to('admin/streams') }}">{{trans('app.Streams Management')}}</a></li>
                <?php } ?>
            </ol>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
    </div>
    <!-- row -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if(isset($pid)){ ?>
                    <h3 class="content-heading mt-2">{{$usr_name.'\'s '.trans('app.Streams')}}</h3>
                <?php }else{ ?>
                    <h3 class="content-heading mt-2">{{trans('app.Streams Management')}}</h3>
                <?php } ?>
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
                                        <?php if(isset($pid)){ 
                                            $sea = 'streamsearch';
                                        }else{
                                            $sea = 'search';
                                        } ?>
                                        {{ Form::open(array('url' => 'admin/streams/'.$sea, 'method' => 'get')) }}
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control search_filter" placeholder="Search by title" maxlength="30" name="search" value="<?php if (isset($search)) {
                                                echo $search;
                                            } ?>">
                                            <input type="hidden" name="sort" value="<?php if(isset($sortby)){                                            echo $sortby; }else{ echo 'created_at'; } ?>">
                                            <input type="hidden" name="direction" value="<?php if(isset($sortorder)){ echo $sortorder; }else{ echo 'desc'; } ?>">
                                            <?php if(isset($pid)){ ?>
                                                <input type="hidden" name="pid" value="<?php if(isset($pid)){ echo $pid; }else{ echo ''; } ?>"><?php }else{ ?>
                                                    <input type="hidden" name="status" value="<?php if(isset($filterby)){ echo $filterby; }else{ echo 'active'; } ?>">
                                                <?php } ?>
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
                                <table class="table header-border table-responsive-sm" style="text-align: center;">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __("S.no")}}</th>
                                            <!-- <th scope="col">@sortablelink('name',trans('app.Stream ID'))</th> -->
                                            <th scope="col">@sortablelink('title',trans('app.Title'))</th>
                                            <th scope="col">{{trans('app.Publisher Name')}}</th>
                                            <th scope="col">&nbsp;&nbsp;{{trans('app.Likes')}}&nbsp;&nbsp;</th>
                                            <th scope="col">&nbsp;&nbsp;{{trans('app.Views')}}&nbsp;&nbsp;</th>
                                            <th scope="col">{{trans('app.Report')}}</th>
                                            <th scope="col">@sortablelink('thumbnail',trans('app.Thumbnail'))</th>
                                            <th scope="col">@sortablelink('active_status',trans('app.Status'))</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $id =1; @endphp
                                        @if(!empty($streams))
                                        @foreach($streams as $trans)
                                        @php $name = Accounts::where('_id', $trans['publisher_id'])->first();
                                        $idd = new \MongoDB\BSON\ObjectID($trans['_id']);
                                        $offset = (string)$idd;
                                        @endphp
                                        <tr>
                                            <td>{{$id}}</td>
                                            <!-- <td>{{$trans['_id']}}</td> -->
                                            <td style="word-break: break-word;" title="{{ucfirst($trans['title'])}}">{{ ucfirst($trans['title'])}} 
                                             @if($trans['mode'] === "public") <a href="javascript:void(0);" data-original-title="Public Stream"><i class="fa fa-eye" style="color:#4bad91;" title="Public Stream"></i></a> @else <a href="javascript:void(0);" title="Private Stream"><i class="fa fa-lock" style="color:#4bad91;"></i></a> @endif
                                            </td>
                                            <td style="word-break: break-word;"><a href="{{ URL::to('admin/accounts/show/' . $name['_id']) }}" id="linkk" target="_blank" class="mr-4">{{$name['acct_name']}}</a></td>
                                            <td>{{$trans['likes']}} &nbsp; <i class="fa fa-thumbs-up" style="color:#4bad91;"></i></td>
                                            <td>{{$trans['viewers']}} &nbsp; <i class="fa fa-eye" style="color:#4bad91;"></i></td>
                                            <td><?php if($reportcounts[$offset] > 0){ ?>
                                                <a href="{{ URL::to('admin/reports/show/' . $trans['_id']) }}" style="cursor: pointer;" class="badge mb-2 mb-xl-0 badge-rounded badge-danger">{{trans('app.View')}} </a>
                                                <?php }else{ echo "No report found"; } ?></td>
                                                <td><?php if(file_exists( public_path().'/img/streams//'.$trans['thumbnail'] )) { ?><img src="{{url('/public/img/streams/'.$trans['thumbnail'])}}" style="height:120px;"><?php }else{ echo 'No image'; } ?></td>
                                                <td>@if($trans['active_status'] ==0)
                                                    <a href="{{ URL::to('admin/streams/changestatus/' . $trans['_id']).'/1' }}" style="cursor: pointer;" class="badge mb-2 mb-xl-0 badge-rounded badge-danger action_btn_inact_stream">{{trans('app.Deactive')}} </a>
                                                    @elseif($trans['active_status'] ==1)
                                                    <a href="{{ URL::to('admin/streams/changestatus/' . $trans['_id']).'/0' }}" style="cursor: pointer;" class="badge mb-2 mb-xl-0 badge-rounded badge-dark action_btn_act_stream">{{trans('app.Active')}} </a>
                                                @endif</td>
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