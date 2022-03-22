@extends('back/organization/layouts/master')

@section('title')
	@lang('layout.reports')
@endsection

@section('one-step')
    / Report
@endsection

@push('date-styles')
	<link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endpush

@section('page-nav')
	<h4>Reports</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">@lang('layout.home')</a></li>
		<li class="breadcrumb-item active">@lang('layout.reports')</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-block bg-white">
				<h5 class="mb-1">@lang('layout.generatereport')</h5>
				<form action="{{ route('report.generate') }}" method="POST">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-4">
							<label>@lang('layout.typeofresource')</label>
							<select name="resource_type" id="resource" class="form-control" required>
								<option value="" disabled="" selected="">@lang('layout.pickaresource')</option>
                                <option value="user">@lang('layout.user')</option>
                                <option value="loan">@lang('layout.loan')</option>
                                <option value="transaction">@lang('layout.transaction')</option>
                            </select>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>@lang('layout.startdate')</label>
								<div class="form-group">
									<input type="date" class="form-control" name="from_date" value="{{ date('d/m/Y') }}" required>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<label>@lang('layout.enddate')</label>
							<div class="form-group">
								<input type="date" class="form-control" name="to_date" value="{{ date('d/m/Y') }}" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>@lang('layout.title')</label>
								<input type="text" class="form-control" name="title" placeholder="eg {{ date('F') . ' ' . date('Y') }}" required id="doc-title">
							</div>
						</div>
						<div class="col-md-4">
							<label>@lang('layout.sortcriteria')</label>
							<select name="sort_by" id="" class="form-control" required>
                                <option value="" disabled="" selected="">@lang('layout.picksortcriteria')</option>
                                <option value="created_at">@lang('layout.createdat')</option>
                            </select>
						</div>
						<div class="col-md-4">
							<label>@lang('layout.nameofsaveddocument')</label>
							<div class="date">
								<input type="text" class="form-control" name="file-name" placeholder="eg {{ date('F') . '-Document' }}" required id="doc-name">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label><span id="type-text">@lang('layout.resourcecriteria')</span></label>
							<select name="type_criteria" id="criteria" class="form-control" required>
                                <option value="" disabled="" selected="">@lang('layout.pickcriteria')</option>
                            </select>
						</div>
						<div class="col-md-6">
							<label>@lang('layout.typeofreport')</label>
							<select name="type" id="" class="form-control" required>
	                            <option value="" disabled selected>@lang('layout.type')</option>
	                            <option value="pdf">@lang('layout.pdf')</option>
	                            <option value="excel">@lang('layout.excel')</option>
	                        </select>
						</div>
					</div>
					<br>
					<div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-block">@lang('layout.generatereport')</button>
                        </div>
                    </div>
				</form>
			</div>
		</div>
	</div>
@endsection

@push('date-scripts')
	<script type="text/javascript" src="{{ asset('plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}/"></script>
	<script type="text/javascript" src="{{ asset('plugins/moment/moment.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
@endpush

@push('scripts')
	<script src="{{ asset('js/back/reports.js') }}"></script>
	<script src="{{ asset('js/back/forms-pickers.js') }}"></script>
@endpush
