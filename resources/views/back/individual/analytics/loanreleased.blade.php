@extends('back/individual/layouts/master')

@section('title')
	Loan Released
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
                    <h4>Loan Released</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <canvas id="myChart2" height="180"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h4>Loan Released</h4>
                </div>
                <div class="card-body">
                    <div id="loan-collections">

                    </div>
                    <div id="loan-collections" class="table-responsive">
                        <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
                            <thead>
                            <tr>
                                <th>Loan Title</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            @foreach($loans as $loan)
                              <tbody>
                                <tr>
                                  <td>{{ $loan->loan_title }}</td>
                                  <td>{{ $loan->currency }} {{ $loan->amount }}</td>
                                  <td>
                                    @if($loan->status == 0)
                                      Pending
                                    @elseif($loan->status == 1)
                                      Approved
                                    @elseif($loan->status == 2)
                                      Declined
                                    @endif
                                  </td>
                                  <td>{{ $loan->created_at }}</td>
                                </tr>
                              </tbody>
                            @endforeach
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
    <!-- <script src="{{ asset('assets/js/page/loan-released.js') }}"></script> -->

    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>

    <!-- Custom JS File -->
    <script src="{{ asset('assets/js/custom.js')}}"></script>

    <script type="text/javascript">
        var loan_released = {!! json_encode($loan_released) !!};
        var loan_released = Object.values(loan_released);

        var loan_unreleased = {!! json_encode($loan_unreleased) !!};
        var loan_unreleased = Object.values(loan_unreleased);

        var months = {!! json_encode($months) !!}
        var months = Object.keys(months);

        // console.log(loan_pending);

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


        var ctx = document.getElementById("myChart2").getContext('2d');
        var myChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: months,
            datasets: [{
              label: 'Released',
              data: loan_released,
              borderWidth: 2,
              backgroundColor: 'rgb(36, 160, 209)',
              borderColor: 'rgba(255,164,38,.9)',
              borderWidth: 2.5,
              pointBackgroundColor: '#ffffff',
              pointRadius: 4
            }, {
              label: 'Not Released',
              data: loan_unreleased,
              borderWidth: 2,
              backgroundColor: 'rgb(196, 33, 33)',
              borderColor: 'transparent',
              borderWidth: 0,
              pointBackgroundColor: '#999',
              pointRadius: 4
            }]
          },
          options: {
            legend: {
              display: false
            },
            scales: {
              yAxes: [{
                gridLines: {
                  drawBorder: false,
                  color: '#f2f2f2',
                },
                ticks: {
                  beginAtZero: true,
                  stepSize: 10,
                  fontColor: "#9aa0ac", // Font Color
                }
              }],
              xAxes: [{
                gridLines: {
                  display: false
                },
                ticks: {
                  fontColor: "#9aa0ac", // Font Color
                }
              }]
            },
          }
        });

        var chart = new ApexCharts(document.querySelector("#revenue"), options);

        chart.render();
    </script>
@endsection
