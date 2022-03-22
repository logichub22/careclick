@extends('back/organization/layouts/master')

@section('title')
	Edit Loan Package
@endsection

@section('page-nav')
	<h4>Edit Loan Package</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Lending</a></li>
		<li class="breadcrumb-item"><a href="{{ route('org-packages.index') }}">Packages</a></li>
        <li class="breadcrumb-item active">Edit Package</li>
	</ol>
@endsection

@section('content')
    @if (count($loans) > 0)
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-warning">
					Please note that you can't edit some fields since your package has {{ count($loans) }} active loans.
				</div>
			</div>
		</div>
	@endif

	
@endsection