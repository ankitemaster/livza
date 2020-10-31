@extends('admin.layouts.sidebar')
@section('content')

        <!--**********************************
            Content body start
        ***********************************-->
        
        <?php
$val = implode(",", $title);
?>
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
                            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/reports/create') }}">{{trans('app.Update Report Titles')}}</a></li>
                        </ol>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
                    
                    </div>
                            <!-- row -->

                            <div class="container">
                            <div class="row">
                            <div class="col-12">
                            <h3 class="content-heading mt-2">{{trans('app.Update Report Titles')}}</h3>
                        </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="form-validation">
                                    <form class="form-valide" action="{{action('Admin\ReportsController@store')}}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">{{trans('app.Report Title')}} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                       <textarea name="summernoteInput" id="val-reporttitles" maxlength="300" class="note">{{$val}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9" id="descriperr" style="color: #fd397a;">
                                                    @if ($errors->has('summernoteInput'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('summernoteInput') }}</strong>
                                                        </span>
                                                    @endif
                                                    </div>
                                                 </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9" style="color: #fd397a;">{{trans('app.Note : Seperate your report titles with comma(,)')}}
                                                    </div>
                                                 </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9" id="descriperr" style="color: #fd397a;">
                                                    </div>
                                                 </div>
                                                 <div class="form-group row">
                                                    <div class="col-lg-9 ml-auto">
                                                        <button type="submit" class="btn btn-primary">{{trans('app.Save')}}</button>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        
        <style type="text/css">
            .note{
                margin-top: 0px;
                margin-bottom: 0px;
                color: #fff;
                padding: 0.375rem 0.75rem;
                background-color: black;
                border-color: #603884;
                width: 100%;
                height: 89px;
            }
        </style>

@endsection

