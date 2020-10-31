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
            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/helps/show/'.$helps->id) }}">{{trans('app.View Help Query')}}</a></li>
        </ol>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        &nbsp;
    </div>
</div>

    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <h3 class="content-heading" style="margin-bottom: 0px;margin-top: 0px;">{{trans('app.View Help Query')}}</h3>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <a class="btn btn-primary mb-2" href="{{ URL::previous() }}">{{trans('app.Back')}}</a>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="container">

        <div class="col-md-12">
            <div class="card block-element-1">
                <div class="card-body">
                    <div>
                        <h4 class="card-title mb-4">{{$helps->help_title}}</h4>
                        <p><?php echo $helps->help_descrip; ?></p>
                    </div>

                </div>
                <div class="card-footer d-sm-flex justify-content-between">
                    <div class="card-footer-link mb-4 mb-sm-0">
                        <p class="card-text text-dark d-inline" style="color: #fff!important">{{trans('app.Last updated')}} {{Carbon::createFromTimestamp(strtotime($helps->updated_at->format('r')))->diffForHumans()}}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!--**********************************
            Content body end
        ***********************************-->
@endsection 