@extends('back/organization/layouts/master')

@section('title')
	@lang('layout.accesslogs')
@endsection

@section('page-nav')
	<h4>@lang('layout.accesslogs')</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">@lang('layout.home')</a></li>
		<li class="breadcrumb-item active">@lang('layout.accesslogs')</li>
	</ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('layout.allaccesslogs')</h5>
                <table class="table table-hover table-bordered table-2">
                    <thead>
                        <tr>
                            <th>@lang('layout.user')</th>
                            <th>@lang('layout.phonenumber')</th>
                            <th>@lang('layout.email')</th>
                            <th>@lang('layout.ipaddress')</th>
                            <th>@lang('layout.locationofaccess')</th>
                            <th>@lang('layout.time')</th>
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
                                          @lang('notavailable')
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