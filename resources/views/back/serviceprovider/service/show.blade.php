@extends('back/organization/layouts/master')

@section('title')
	View Service
@endsection

@section('page-nav')
	<h4>Service Section</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('services.index') }}">My Services</a></li>
        <li class="breadcrumb-item active">View Service</li>
	</ol>
@endsection

@section('content')
    <div class="row">  
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p>Name: {{ $service->name }}</p>
                    <p>Description: {{ $service->description }}</p>
                    <p>Clients: 0</p>
                    <p>Status: Active</p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-1">Clients</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover table-2">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($service->id == 1)
        <div class="card">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header">
                        <h5 class="mb-1">Available Packages</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-2">
                            <thead>
                                <tr>
                                    <th>Package Name</th>
                                    <th>Status</th>
                                    <th>Registered Clients</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
        
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection