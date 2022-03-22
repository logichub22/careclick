@extends('back/superadmin/layouts/master')

@section('title')
	Access Logs
@endsection

@section('one-step')
    / Access Logs
@endsection

@section('page-nav')
	<h4>Access Logs</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('super.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item active">Access Logs</li>
	</ol>
@endsection

@section('content')
    <div class="row">
   @section('spec-styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h4>All Access Logs</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                    <th>Ip Address</th>
                                    <th>Location of Access</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td>{{ $log->name. ' ' . $log->other_names }}</td>
                                        <td>{{ $log->msisdn }}</td>
                                        <td>{{ $log->email }}</td>
                                        <td>
                                            {{ $log->ip_address }}
                                        </td>
                                        <td>
                                            @if(empty($log->location))
                                                Not Available
                                            @else
                                                {{ $log->location }}
                                            @endif
                                        </td>
                                        <td>{{ $log->access_time }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('spec-scripts')
    <script src="{{ asset('assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/datatables.js') }}"></script>
@endsection