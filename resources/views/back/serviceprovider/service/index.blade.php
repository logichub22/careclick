@extends('back/organization/layouts/master')

@section('title')
	Your Services
@endsection

@section('page-nav')
	<h4>Service Section</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item active">Services</li>
	</ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-block bg-white">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Service Name</th>
                            <th>Service Description</th>
                            <th>Your Clients</th>
                            <th>Service Status</th>
                            <th>Date Added</th>
                            <th>More Details</th>
                        </tr>
                    </thead>
                    @if (count($services) > 0)
                        @foreach ($services as $service)
                            <tr>
                                <td>{{ $service->name }}</td>
                                <td>{{ $service->description }}</td>
                                <td>0</td>
                                <td>
                                    @if ($service->status)
                                    Active 
                                    @else
                                    Inactive
                                    @endif
                                </td>
                                <td>{{ $service->created_at }}</td>
                                <td class="text-center">
                                    <a href="{{ route('services.show', $service->id) }}" class="btn btn-primary">More Details</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">You have no services. Add one <a href="{{ route('services.create') }}">here</a></td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection