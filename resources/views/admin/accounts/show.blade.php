@extends('admin.layouts.sidebar')
@section('content')
<?php
use Carbon\Carbon;
use App\Classes\MyClass;
$myClass = new MyClass();
$settings = $myClass->site_settings();
?>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/accounts/show/'.$accounts->id) }}">{{trans('app.View Accounts')}}</a></li>
        </ol>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
</div>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <h3 class="content-heading" style="margin-bottom: 0px;margin-top: 0px;">{{trans('app.View Account')}}</h3> 
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            <a class="btn btn-primary mb-2" href="{{ URL::previous() }}">{{trans('app.Back')}}</a>
        </ol>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-4 col-md-5 col-xxl-4 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-center mb-4">
                        <?php 
                        $image = public_path().'/img/accounts/'.$accounts->acct_photo;
                        if (!file_exists($image)) {
                            $image = '/public/img/user.png';
                        }
                        else
                        {
                            $image = '/public/img/accounts/'.$accounts->acct_photo;
                        }
                        ?>
                        {{ Html::image($image, 'user', array('class' => 'mr-3 rounded-circle mr-0 mr-sm-3','width' => '80', 'height' => '80')) }}
                        <div class="media-body">
                            <h3 class="mb-0" style="    word-break: break-all;">{{$accounts->acct_name}}</h3>
                            <p class="text-muted mb-0 mt-2"><img src="{{url('/public/img/gems.png')}}" style="width: 20px;height: 18px;">   @if($accounts->acct_gems != ''){{$accounts->acct_gems}}@else{{0}}@endif</p>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col">
                            <a href="{{URL::to('admin/accounts/follow/following/'.$accounts->id)}}">
                                <div class="card-profile border-0 text-center">
                                    <span class="mb-1 text-primary"><i class="icon-people"></i></span>
                                    <h3 class="mb-0">{{$followings_count}}</h3>
                                    <p class="text-muted px-4">{{trans('app.Following')}}</p>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{URL::to('admin/accounts/follow/follower/'.$accounts->id)}}">
                                <div class="card-profile border-0 text-center">
                                    <span class="mb-1 text-warning"><i class="icon-user-follow"></i></span>
                                    <h3 class="mb-0">{{$followers_count}}</h3>
                                    <p class="text-muted">{{trans('app.Followers')}}</p>
                                </a>
                            </div>
                        </div>
                        <div class="col">
                            <a href="{{URL::to('admin/streams/userstreams/'.$accounts->id)}}">
                                <div class="card-profile border-0 text-center">
                                    <span class="mb-1 text-warning"><i class="icon-film"></i></span>
                                    <h3 class="mb-0">{{$stream_count}}</h3>
                                    <p class="text-muted">{{trans('app.Streams')}}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                       @if($accounts->acct_status)
                       <span class="badge mb-2 mb-xl-0 badge-danger">{{trans('app.Active')}}</span>
                       @else
                       <span class="badge mb-2 mb-xl-0 badge-secondary">{{trans('app.Inactive')}}</span>
                       @endif
                   </div>
                   <h4 class="col-md-12">{{trans('app.About')}}</h4>
                   <p class="text-muted"></p>
                   <ul class="card-profile__info">
                    <p class="text-muted text-center">
                        @if($accounts->acct_sync === 'facebook')
                        <a href="javascript:void()" class="badge mb-2 mb-xl-0 badge-rounded badge-info">{{trans('app.Facebook Account Verified')}}</a>
                        @elseif($accounts->acct_sync === 'apple')
                        <a href="javascript:void()" class="badge mb-2 mb-xl-0 badge-rounded badge-info">{{trans('app.Apple ID Verified')}}</a>
                        @else
                        <a href="javascript:void()" class="badge mb-2 mb-xl-0 badge-rounded badge-info">{{trans('app.Mobile number Verified')}}</a>
                        @endif
                    </p>
                    <table class="table mb-0 table-responsive-tiny">
                        <tbody>
                            <tr>
                                <td style="width: 40%;" class="text-dark"><strong>{{trans('app.Age')}}</strong></td><td>{{$accounts->acct_age}}</td>
                            </tr>
                            @if($accounts->acct_sync == 'phonenumber')
                            <tr>
                                <td style="width: 40%;" class="text-dark"><strong>{{trans('app.Mobile no')}}</strong></td><td>{{$accounts->acct_phoneno}}</td>
                            </tr>
                            @else
                            <tr>
                                <td style="width: 40%;" class="text-dark"><strong>{{trans('app.Email')}}</strong></td><td>{{$accounts->acct_mailid}}</td>
                            </tr>
                            @endif
                            <tr>
                                <td style="width: 40%;" class="text-dark"><strong>{{trans('app.Birth date')}}</strong></td><td>{{$accounts->acct_birthday->toDateTime()->format('M d, Y')}}</td>
                            </tr>
                            <tr>
                                <td class="text-dark"><strong>{{trans('app.Gender')}}</strong></td><td>{{ucfirst($accounts->acct_gender)}}</td>
                            </tr>
                            <tr>
                                <td class="text-dark"><strong>{{trans('app.Location')}}</strong></td><td>{{ucfirst($accounts->acct_location)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-md-7 col-xxl-8 col-xl-9">
        <div class="card">
            <div class="card-body">
                <form action="{{url('admin/accounts/sendalert/'.$accounts->id)}}" class="form-profile" method="get" onsubmit="return validatemsg()">
                    <div class="form-group">
                        <textarea class="form-control" name="msg" id="msg" cols="30" rows="5" placeholder="{{trans('app.Type new notification message')}}" style="background: rgba(0, 0, 0, 0.4);"></textarea>
                        @if ($errors->has('msg'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('msg') }}</strong>
                        </span>
                        @endif
                        <span class="text-danger" id="msgerr"></span>
                    </div>
                    <div class="align-items-center text-right">
                        <button class="btn btn-primary px-3">{{trans('app.Send')}}</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
               <div class="custom-tab-1">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#success1">{{trans('app.Device Detail')}}</a>
                    </li>
                </ul>
                <div class="tab-content tab-content-default">
                    <div class="tab-pane fade show active" id="success1" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    @if(!empty(count($device)))
                                    <table class="table table-striped table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{trans('app.Device Model')}}</th>
                                                <th>{{trans('app.type')}}</th>
                                                <th>{{trans('app.Logged in')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <?php $id = 1; ?>
                                           @foreach($device as $dev)
                                           <tr>
                                            <th>{{$id}}</th>
                                            <td>{{$dev->device_model}}</td>
                                            @if($dev->device_type == 0)
                                            <td><span class="badge mb-2 mb-xl-0 badge-warning">IOS</span>
                                                @else
                                                <td><span class="badge mb-2 mb-xl-0 badge-success" style="color: #000;">Android</span>
                                                    @endif
                                                </td>
                                                <td><i class="fa fa-clock-o" aria-hidden="true"></i> 
                                                    <?php
                                                    try {
                                                        $date = new DateTime($dev->notified_at->toDateTime()->format('M d, H:i a'), new DateTimeZone('UTC'));
                                                        $date->setTimezone(new DateTimeZone($settings->timezone));
                                                        echo $date->format('M d, h:i a');
                                                    } catch (\Throwable $th) {
                                                      echo $dev->notified_at->toDateTime()->format('M d, H:i a');
                                                  }
                                                  ?></td>
                                              </tr>
                                              <?php $id++; ?>
                                              @endforeach
                                          </tbody>
                                      </table>
                                      <div class="pagination-wrapper" style="float: right;"> {!! $device->render() !!} </div>
                                      @else
                                      <span><i class="fa fa-exclamation-triangle" aria-hidden="true" style="    color: yellow;"></i> {{trans('app.No device details')}}</span>
                                      @endif
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
</div>
@endsection