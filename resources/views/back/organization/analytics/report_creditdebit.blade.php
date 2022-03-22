@extends('back/organization/layouts/master')

@section('title')
	Reports
@endsection

@section('one-step')
    / Report
@endsection

@section('spec-styles')
	<link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
	<div class="row">
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h4>@lang('layout.generatereport')</h4>
                    <form action="{{ route('organization.reportw_get') }}" method="POST">
					{{ csrf_field() }}
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>@lang('layout.typeofreport')</label>
							<select name="resource_type" id="resource" class="form-control" required>
								<option value="" disabled="" selected="">@lang('layout.pickareport')</option>
                                <option value="user">@lang('layout.user')</option>
                                <option value="loan">@lang('layout.loan')</option>
                                <option value="transaction">@lang('layout.transaction')</option>
                                <option value="revenue">@lang('layout.revenue')</option>
                                <option value="principal">Principal</option>
                            </select>
                        </div>

                       <div class="form-group col-md-6">
                            <label>@lang('layout.title')</label>
								<input type="text" class="form-control" name="title" placeholder="eg {{ date('F') . ' ' . date('Y') }}" required id="doc-title">
                        </div>

                        <div class="form-group col-md-6">
                            <label>@lang('layout.startdate')</label>
								<div class="form-group">
									<input type="date" class="form-control" name="from_date" value="{{ date('d/m/Y') }}" required>
								</div>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label>@lang('layout.enddate')</label>
							<div class="form-group">
								<input type="date" class="form-control" name="to_date" value="{{ date('d/m/Y') }}" required>
							</div>
						</div>
                        <div class="form-group col-md-6">
                            <label>@lang('layout.sortcriteria')</label>
							<select name="sort_by" id="" class="form-control" required>
                                <option value="" disabled="" selected="">@lang('layout.picksortcriteria')</option>
                                <option value="created_at">@lang('layout.createdat')</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>@lang('layout.nameofsaveddocument')</label>
							<div class="date">
								<input type="text" class="form-control" name="file_name" placeholder="eg {{ date('F') . '-Document' }}" required id="doc-name">
							</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label><span id="type-text">@lang('layout.resourcecriteria')</span></label>
							<select name="type_criteria" id="criteria" class="form-control" required>
                                <option value="" disabled="" selected="">@lang('layout.pickcriteria')</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>@lang('layout.typeofreport')</label>
							<select name="type" id="" class="form-control" required>
	                            <option value="" disabled selected>@lang('layout.type')</option>
	                            <!-- <option value="pdf">@lang('layout.pdf')</option>
	                            <option value="excel">@lang('layout.excel')</option> -->
                                <option value="csv">CSV</option>
	                        </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block">@lang('layout.generatereport')</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('spec-scripts')
	<script type="text/javascript" src="{{ asset('plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}/"></script>
	<script type="text/javascript" src="{{ asset('plugins/moment/moment.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

	<script src="{{ asset('js/back/reports.js') }}"></script>
	<script src="{{ asset('js/back/forms-pickers.js') }}"></script>
@endsection
