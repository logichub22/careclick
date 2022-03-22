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
                    <h4>Loan Maturity</h4>
                </div>
                <div class="card-body">
                    <!-- <div class="row">
                        <div class="col-md-6">
                             <input class="form-control" type="text" name="daterange" value="01/01/2015 - 01/31/2015">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-block">Apply Filter (WIP)</button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-primary btn-block">Download as Excel (WIP)</button>
                        </div>
                    </div> -->
                    <br>
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
                    <h4>Loan Maturity</h4>
                </div>
                <div class="card-body">
                    <div id="loan-collections">

                    </div>
                    <div id="loan-collections" class="table-responsive">
                        <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
                            <thead>
                            <tr>
                                <th>Transaction Code</th>
                                <th>Amount</th>
                                <th>Transaction Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>First Loan</td>
                                <td>$50,000</td>
                                <td>11:50:00 20-5-2020</td>
                            </tr>
                            <tr>
                                <td>Second Loan</td>
                                <td>$6,000</td>
                                <td>9:50:00 20-6-2020</td>
                            </tr>
                            <tr>
                                <td>Third Loan</td>
                                <td>$800</td>
                                <td>2:50:00 20-7-2020</td>
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
    <script src="{{ asset('assets/js/page/loan-maturity.js') }}"></script>
    
    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    
    <!-- Custom JS File -->
    <script src="{{ asset('assets/js/custom.js')}}"></script>
@endsection
