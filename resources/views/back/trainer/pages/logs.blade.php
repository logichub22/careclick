@extends('back/organization/layouts/master')

@section('title')
	Access Logs
@endsection

@section('page-nav')
	<h4>Access Logs</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item active">Access Logs</li>
	</ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-block bg-white">
                <h5 class="mb-1">All Access Logs</h5>
                <table class="table table-hover table-bordered table-2">
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
@endsection