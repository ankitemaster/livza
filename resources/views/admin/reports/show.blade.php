@extends('admin.layouts.sidebar')
<?php use Carbon\Carbon; ?>

@section('content')
<?php
use App\Models\Accounts;
use App\Models\Streams;
use App\Classes\MyClass;
$myClass = new MyClass();
$settings = $myClass->site_settings();
?>
<!--**********************************
            Content body start
        ***********************************-->

        <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('app.View User\'s Report')}}</a></li>
                        </ol>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
                    
                    </div>

  <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
      <h3 class="content-heading" style="margin-bottom: 0px;margin-top: 0px;">{{trans('app.User\'s Report')}}</h3>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
      <ol class="breadcrumb">
        <a class="btn btn-primary mb-2" href="{{ URL::previous() }}">{{trans('app.Back')}}</a>
      </ol>
    </div>
  </div>
  <!-- row -->
  <div class="container">
    <div class="row">
      @foreach($reports as $report)
      <?php
      $user_det = Accounts::find($report->user_id);
      $stream_det = Streams::find($report->stream_id);
      ?>
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="bootstrap-media">
              <ul class="list-unstyled">
                <li class="media">
                  <img id="myImg" class="card-img-top img-fluid" src="{{url('/public/img/streams/'.$stream_det->thumbnail)}}" alt="{{trans('app.Screenshot to verify')}}" style="width: 150px;max-width: 200px;height: auto;">
                  <div class="media-body ml-5">
                    <h4 class="mt-0 mb-1">{{ucfirst($report->report)}}</h4>
                    <div class="row mt-4">
                      <div class="col-xl-36 col-xxl-12 col-lg-12 col-md-6">
                        <div class="card">
                          <div class="card-body">
                            <h5 class="card-title" style="font-size: 13px!important;">{{trans('app.Reported By')}} <a href="{{ URL::to('admin/accounts/show/' . $user_det->id) }}" id="linkk" target="_blank" class="mr-4">{{$user_det->acct_name}}</a></h5>
                            <div class="basic-list-group card">
                              <ul class="list-group">
                                @if($user_det->acct_status ==0)
                                <li class="list-group-item">{{trans('app.Click here to active this account')}} <a href="{{ URL::to('admin/reports/changestatus/' . $user_det->id).'/1/'. $id}}" style="cursor: pointer;float: right;" class="badge mb-2 mb-xl-0 badge-rounded badge-danger action_btn_act"> {{trans('app.Active')}} </a></li>
                                @else
                                <li class="list-group-item">{{trans('app.Click here to in-active this account')}} <a href="{{ URL::to('admin/reports/changestatus/' . $user_det->id).'/0/'. $id}}" style="cursor: pointer;float: right;" class="badge mb-2 mb-xl-0 badge-rounded badge-dark action_btn_inact"> {{trans('app.Inactive')}} </a></li>
                                @endif
                              </ul>
                            </div>
                            <div class="basic-list-group">
                              <ul class="list-group">
                                @if($stream_det->active_status ==1)
                                <li class="list-group-item">{{trans('app.Click here to active this Stream')}} <a href="{{ URL::to('admin/reports/changestreamstatus/' . $stream_det->id).'/0/'. $id}}" style="cursor: pointer;float: right;" class="badge mb-2 mb-xl-0 badge-rounded badge-danger action_btn_act_stream"> {{trans('app.Active')}} </a></li>
                                @else
                                <li class="list-group-item">{{trans('app.Click here to deactive this Stream')}} <a href="{{ URL::to('admin/reports/changestreamstatus/' . $stream_det->id).'/1/'. $id}}" style="cursor: pointer;float: right;" class="badge mb-2 mb-xl-0 badge-rounded badge-dark action_btn_inact_stream"> {{trans('app.Inactive')}} </a></li>
                                @endif
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                  </div>
                </li>
              </ul>
            </div>
          </div>
          <div class="card-footer d-sm-flex justify-content-between">
            <div class="card-footer-link mb-4 mb-sm-0">
              <p class="card-text text-dark d-inline" style="color: #fff!important">{{trans('app.Reported on')}} 
              <?php
                                    
                                    try {
                                      $date = new DateTime($report->reported_at->toDateTime()->format('M d, H:i a'), new DateTimeZone('UTC'));
                      
                                      $date->setTimezone(new DateTimeZone($settings->timezone));
                                      echo $date->format('M d, h:i a');
                                    } catch (\Throwable $th) {
                                        echo $report->reported_at->toDateTime()->format('M d, H:i a');
                                    }
                                    ?>


              </p>
            </div>
          </div>
        </div>
        <div id="myModal" class="modal">
          <span class="close">&times;</span>
          <img class="modal-content" id="img01">
          <div id="caption"></div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

<script type="text/javascript">

  // Get the modal
  var modal = document.getElementById('myModal');
  // Get the image and insert it inside the modal - use its "alt" text as a caption
  var img = document.getElementById('myImg');
  var modalImg = document.getElementById("img01");
  var captionText = document.getElementById("caption");
  img.onclick = function() {
    modal.style.display = "block";
    modalImg.src = this.src;
    captionText.innerHTML = this.alt;
  }
  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("close")[0];
  // When the user clicks on <span> (x), close the modal
  span.onclick = function() {
    modal.style.display = "none";
  }
</script>
<style>
  body {
    font-family: Arial, Helvetica, sans-serif;
  }

  #myImg {
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
  }

  #myImg:hover {
    opacity: 0.7;
  }

  /* The Modal (background) */
  .modal {
    display: none;
    /* Hidden by default */
    position: fixed;
    /* Stay in place */
    z-index: 33333;
    /* Sit on top */
    padding-top: 100px;
    /* Location of the box */
    left: 0;
    top: 0;
    width: 100%;
    /* Full width */
    height: 100%;
    /* Full height */
    overflow: auto;
    /* Enable scroll if needed */
    background-color: rgb(0, 0, 0);
    /* Fallback color */
    background-color: rgba(0, 0, 0, 0.9);
    /* Black w/ opacity */
  }

  /* Modal Content (image) */
  .modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
  }

  /* Caption of Modal Image */
  #caption {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    text-align: center;
    color: #ccc;
    padding: 10px 0;
    height: 150px;
  }

  /* Add Animation */
  .modal-content,
  #caption {
    width: 300px;
    -webkit-animation-name: zoom;
    -webkit-animation-duration: 0.6s;
    animation-name: zoom;
    animation-duration: 0.6s;
  }

  @-webkit-keyframes zoom {
    from {
      -webkit-transform: scale(0)
    }

    to {
      -webkit-transform: scale(1)
    }
  }

  @keyframes zoom {
    from {
      transform: scale(0)
    }

    to {
      transform: scale(1)
    }
  }

  /* The Close Button */
  .close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
  }

  .close:hover,
  .close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
  }

  /* 100% Image Width on Smaller Screens */
  @media only screen and (max-width: 700px) {
    .modal-content {
      width: 100%;
    }
  }

  #linkk {
    color: #179c8e !important;
  }

  #linkk:hover {
    color: #179c8e !important;
  }
</style>
<!--**********************************
            Content body end
        ***********************************-->
@endsection