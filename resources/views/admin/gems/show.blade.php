@extends('admin.layouts.sidebar')
  <?php use Carbon\Carbon; ?>
@section('content')
  <!--**********************************
            Content body start
        ***********************************-->
        

            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <h3 class="content-heading" style="margin-bottom: 0px;margin-top: 0px;">View gem package</h3> 
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <a class="btn btn-primary mb-2" href="{{ URL::previous() }}">Back</a>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                               
                                <div class="bootstrap-media">
                                    <div class="media">
                                        <div class="media-body">
                                           <!--  <div id="bm"> </div> -->
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="card-footer d-sm-flex justify-content-between">
                                <div class="card-footer-link mb-4 mb-sm-0">
                                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       
       
        
         
       {{ Html::script('public/js/lottie/bodymovin.js') }}

        <script type="text/javascript">
            var data = <?php echo $gem->gem_icon; ?>;
           lottie.loadAnimation({
              container: document.getElementById('bm'), // the dom element that will contain the animation
              renderer: 'svg',
              loop: true,
              autoplay: true,
              animationData: data // the path to the animation json
            });
        </script>

        <!--**********************************
            Content body end
        ***********************************-->
@endsection