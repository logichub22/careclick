@extends('back/individual/layouts/master')

@section('title')
	Graphical Analysis
@endsection

@section('one-step')
    / Report
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
                    <h4>Savings</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                    <div class="col-md-6">
                        <canvas id="donutChart"></canvas>
                    </div>
                    <div class="col-md-3">
                    <ul class="p-t-30 list-unstyled">
                      <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-orange"></i></span>Withdrawn<span
                          class="float-right">30%</span></li>
                      <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-green"></i></span>Saved<span
                          class="float-right">50%</span></li>
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
                                <th>Month</th>
                                <th>Saved</th>
                                <th>Withdrawn</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>June</td>
                                <td>$500</td>
                                <td>$200</td>
                            </tr>
                            <tr>
                                <td>July</td>
                                <td>$1000</td>
                                <td>$500</td>
                            </tr>
                            <tr>
                                <td>August</td>
                                <td>$3000</td>
                                <td>$600</td>
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
    <script src="{{ asset('assets/js/page/savings-chart.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>

    <!-- Custom JS File -->
    <script src="{{ asset('assets/js/custom.js')}}"></script>
@endsection
