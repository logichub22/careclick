@extends('back/organization/layouts/master')

@section('title')
	Graphical Analysis
@endsection

@section('one-step')
    / Analytics
@endsection

@section('spec-styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/bundles/jqvmap/dist/jqvmap.min.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/bundles/flag-icon-css/css/flag-icon.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 col-lg-12">
            <div class="card">

                <div class="card-header">
                    <h4>Gender</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                    <div class="col-md-6">
                        <canvas id="donutChart"></canvas>
                    </div>
                    <div class="col-md-3">
                    <ul class="p-t-30 list-unstyled">
                      <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-black"></i></span>Male<span
                          class="float-right">{{ $male_percentage }}%</span></li>
                      <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-green"></i></span>Female<span
                          class="float-right">{{ $female_percentage }}%</span></li>
                      <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-orange"></i></span>Not Specified<span
                          class="float-right">{{ $not_specified_percentage }}%</span></li>
                    </ul>
                </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h4>Gender</h4>
                </div>
                <div class="card-body">
                    <div id="loan-collections">

                    </div>
                    <div id="loan-collections" class="table-responsive">
                        <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
                            <thead>
                            <tr>
                                <th>Gender</th>
                                <th>Percentage</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Male</td>
                                <td>{{ $male_percentage }}%</td>
                            </tr>
                            <tr>
                                <td>Female</td>
                                <td>{{ $female_percentage }}%</td>
                            </tr>
                            <tr>
                                <td>Not Specified</td>
                                <td>{{ $not_specified_percentage }}%</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('spec-scripts')
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/datatables.js') }}"></script>
    
    <script src="{{ asset('assets/bundles/chartjs/chart.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('assets/bundles/jqvmap/dist/maps/jquery.vmap.indonesia.js')}} "></script>

    <!-- Page Specific JS File -->
    <!-- <script src="{{ asset('assets/js/page/gender-chart.js') }}"></script> -->
    
    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    
    <!-- Custom JS File -->
    <script src="{{ asset('assets/js/custom.js')}}"></script>

    <script type="text/javascript">
        var male_percentage = {!! json_encode($male_percentage) !!};

        var female_percentage = {!! json_encode($female_percentage) !!};

        var not_specified_percentage = {!! json_encode($not_specified_percentage) !!};

        "use strict";

        var sparkline_values = [10, 7, 4, 8, 5, 8, 6, 5, 2, 4, 7, 4, 9, 6, 5, 9];
        var sparkline_values_chart = [2, 6, 4, 8, 3, 5, 2, 7];
        var sparkline_values_bar = [10, 7, 4, 8, 5, 8, 6, 5, 2, 4, 7, 4, 9, 10, 7, 4, 8, 5, 8, 6, 5, 2, 4, 7, 4, 9, 8, 6, 5, 2, 4, 7, 4, 9, 10, 2, 4, 7, 4, 9, 7, 4, 8, 5, 8, 6, 5];

        $('.sparkline-inline').sparkline(sparkline_values, {
          type: 'line',
          width: '100%',
          height: '32',
          lineWidth: 3,
          lineColor: 'rgba(87,75,144,.1)',
          fillColor: 'rgba(87,75,144,.25)',
          highlightSpotColor: 'rgba(87,75,144,.1)',
          highlightLineColor: 'rgba(87,75,144,.1)',
          spotRadius: 3,
        });

        $('.sparkline-line').sparkline(sparkline_values, {
          type: 'line',
          width: '100%',
          height: '32',
          lineWidth: 3,
          lineColor: 'rgba(63, 82, 227, .5)',
          fillColor: 'transparent',
          highlightSpotColor: 'rgba(63, 82, 227, .5)',
          highlightLineColor: 'rgba(63, 82, 227, .5)',
          spotRadius: 3,
        });

        $('.sparkline-line-chart').sparkline(sparkline_values_chart, {
          type: 'line',
          width: '100%',
          height: '32',
          lineWidth: 2,
          lineColor: 'rgba(63, 82, 227, .5)',
          fillColor: 'transparent',
          highlightSpotColor: 'rgba(63, 82, 227, .5)',
          highlightLineColor: 'rgba(63, 82, 227, .5)',
          spotRadius: 2,
        });
        $('.sparkline-line-chart2').sparkline(sparkline_values_chart, {
          type: "line",
          width: "100%",
          height: "100",
          lineWidth: 3,
          lineColor: "white",
          fillColor: "transparent",
          highlightSpotColor: "rgba(63,82,227,.1)",
          highlightLineColor: "rgba(63,82,227,.1)",
          spotRadius: 3
        });

        $(".sparkline-bar").sparkline(sparkline_values_bar, {
          type: "bar",
          width: "100%",
          height: "100",
          barColor: "white",
          barWidth: 2
        });

        var ctx = document.getElementById("donutChart").getContext('2d');
        var myChart = new Chart(ctx, {
          type: 'doughnut',
          data: {
            datasets: [{
              data: [
                male_percentage,
                female_percentage,
                not_specified_percentage,
              ],
              backgroundColor: [
                '#191d21',
                '#63ed7a',
                '#ffa426',
              ],
              label: 'Dataset 1'
            }],
            labels: [
              'Male',
              'Female',
              'Not Specified',
            ],
          },
          options: {
            responsive: true,
            legend: {
              position: 'bottom',
              display: false
            },
          }
        });

        var chart = new ApexCharts(document.querySelector("#revenue"), options);
        chart.render();
    </script>
@endsection
