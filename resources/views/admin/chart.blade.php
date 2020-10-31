@php
use App\Classes\MyClass;
$myClass = new MyClass();
$settings = $myClass->site_settings();
@endphp
@extends('admin.layouts.sidebar')
@section('content')

<div class="container">
    <div class="row">&nbsp;</div>
    <div class="row mb-4 mt-4">
        <div class="col-xl-6 col-lg-12">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                <h4 class="card-title">{{trans('app.Accounts Added this week')}}</h4>
                <canvas id="acc_chart" width="400" height="300"></canvas>

<script>
$(function () {
    var ctx = document.getElementById("acc_chart").getContext('2d');
    var options = {
        scales: {
            xAxes: [{
                stacked: true
            }],
            yAxes: [{
                stacked: true
            }]
        },

        title : {
        display : true,
        position : "top",
        fontSize : 18,
        fontColor : "#111"
        },
        legend : {
        display : true,
        position : "bottom"
        }
    };
    var myDoughnutChart = new Chart(ctx, {
        type: 'bar',
        data: {
            datasets: [{
                label: "<?php echo trans('app.Male'); ?>",
                data: <?php echo $macc_data; ?>,
                backgroundColor: 'rgba(128, 255, 128, 0.4)',
                borderColor: 'rgba(128, 255, 128, 1)',
                borderWidth : 1
            },
            {
                label: "<?php echo trans('app.Female'); ?>",
                data: <?php echo $facc_data; ?>,
                backgroundColor: 'rgba(255, 173, 51, 0.4)',
                borderColor: 'rgba(255, 173, 51, 1)',
                borderWidth : 1
            }],

            labels: <?php echo $acc_date; ?>
            
        },
        options: options
    });
});
</script>
                 
                    
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-12">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                <h4 class="card-title">{{trans('app.Most used subscription')}}</h4>
                <canvas id="myChart" width="400" height="300"></canvas>

<script>
$(function () {
    var ctx = document.getElementById("myChart").getContext('2d');
    var options = {

        title : {
        display : true,
        position : "top",
        fontSize : 18,
        fontColor : "#111"
        },
        legend : {
        display : true,
        position : "bottom"
        }
    };
    var myDoughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: <?php echo $sub_data; ?>,
                backgroundColor: [
                    
                    'rgba(54, 162, 235, 0.4)',
                    
                    'rgba(75, 192, 192, 0.4)',
                    'rgba(153, 102, 255, 0.4)',
                    'rgba(255, 99, 132, 0.4)',
                    'rgba(255, 159, 64, 0.4)',
                    'rgba(255, 206, 86, 0.4)',
                ],
                borderColor: [
                    
                    'rgba(54, 162, 235, 1)',
                    
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth : [1, 1, 1, 1, 1]
            }],

            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: <?php echo $sub_title; ?>
            
        },
        options: options
    });
});
</script>
                </div>
            </div>
        </div>
</div>



    <div class="row mb-4 mt-4">
        <div class="col-xl-6 col-lg-12">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                <h4 class="card-title">{{trans('app.Transactions in this week')}}</h4>
                <canvas id="tran_chart" width="400" height="300"></canvas>

<script>
$(function () {
    var ctx = document.getElementById("tran_chart").getContext('2d');
    var options = {

        title : {
        display : true,
        position : "top",
        fontSize : 18,
        fontColor : "#111"
        },
        legend : {
        display : true,
        position : "bottom"
        }
    };
    var myDoughnutChart = new Chart(ctx, {
        type: 'line',
        data: {
            datasets: [{
                label: "<?php echo trans('app.Gems'); ?>",
                data: <?php echo $gtran_data; ?>,
                backgroundColor: 'rgba(255, 219, 77, 0.4)',
                borderColor: 'rgba(255, 219, 77, 1)',
                borderWidth : 1
            },
            {
                label: "<?php echo trans('app.Membership'); ?>",
                data: <?php echo $stran_data; ?>,
                backgroundColor: 'rgba(77, 166, 255, 0.4)',
                borderColor: 'rgba(77, 166, 255, 1)',
                borderWidth : 1
            }],

            labels: <?php echo $tran_label; ?>
            
        },
        options: options
    });
});
</script>
              
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-12">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                <h4 class="card-title">{{trans('app.Most used Gem Package')}}</h4>
                
                <canvas id="gems" width="400" height="300"></canvas>

<script>
$(function () {
    var ctx = document.getElementById("gems").getContext('2d');
    var options = {

        title : {
        display : true,
        position : "top",
        fontSize : 18,
        fontColor : "#111"
        },
        legend : {
        display : true,
        position : "bottom"
        }
    };
    var myDoughnutChart = new Chart(ctx, {
        type: 'pie',
        data: {
            datasets: [{
                data: <?php echo $gem_data; ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.4)',
                    'rgba(54, 162, 235, 0.4)',
                    'rgba(255, 206, 86, 0.4)',
                    'rgba(75, 192, 192, 0.4)',
                    'rgba(153, 102, 255, 0.4)',
                    'rgba(255, 159, 64, 0.4)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth : [1, 1, 1, 1, 1]
            }],

            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: <?php echo $gem_title; ?>
            
        },
        options: options
    });
});
</script>
               
                </div>
            </div>
        </div>

    

<script src="{{ URL::asset('public/admin_assets/main/server/client.js')}}"></script>
<style>
.test
{
  position:absolute;
  bottom:-20px;
  left:calc(50% - 10px);
  display:block;
}
</style>
@endsection