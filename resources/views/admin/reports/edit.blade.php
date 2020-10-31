@extends('admin.layouts.sidebar')
@section('content')

        <!--**********************************
            Content body start
        ***********************************-->
        

            <div class="row page-titles mx-0">
                <div class="col-sm-12 p-md-0">
                    <h3 class="content-heading" style="margin-bottom: 0px;margin-top: 0px;text-align: center;">Update report description</h3>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                </div>
            </div>
            <!-- row -->

            <div class="container">
            <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                               
                                <div class="form-validation">
                                    <form class="form-valide" action="{{action('Admin\ReportdescripController@update',$id)}}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">Report Description <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="val-descrip" name="val-descrip" placeholder="Give a description for report.." value="{{$report->rep_descrip}}" maxlength="90">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9" id="descriperr" style="color: #fd397a;">
                                                         @if ($errors->has('val-descrip'))
                                                    <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('val-descrip') }}</strong>
                                                        </span>
                                                    @endif
                                                    </div>
                                                 </div>

                                                 <div class="form-group row">
                                                    <div class="col-lg-9 ml-auto">
                                                        <button type="submit" class="btn btn-primary">Update</button>
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
            
       
        
@endsection

