@extends('admin.layouts.sidebar')
  <?php use Carbon\Carbon; ?>
@section('content')
  <!--**********************************
            Content body start
        ***********************************-->
        <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('app.View Prime Benefit Slider')}}</a></li>
                        </ol>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
                    
                    </div>

            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <h3 class="content-heading" style="margin-bottom: 0px;margin-top: 0px;">{{trans('app.View Prime Benefit Slider')}}</h3> 
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
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <center><img id="myImg" class="card-img-top img-fluid" src="{{url('/public/img/slider/'.$data['image'])}}" alt="Screenshot to verify" style="width: 150px;max-width: 200px;height: auto;">
                                <h4 class="card-title">{{$data['title']}}</h4>
                                <p> <?php echo $data['description']; ?></p>
                                </center>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
      
        <!--**********************************
            Content body end
        ***********************************-->
@endsection