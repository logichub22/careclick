@extends('back/individual/layouts/master')

@section('title')
	@lang('individual.myloanpackages')
@endsection

@section('one-step')
    / Loan Packages
@endsection

@section('spec-styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
	<div class="row">
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h4>@lang('individual.loanpackages')</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
	                        <thead>
								<tr>
									<th>@lang('individual.name')</th>
									<th>@lang('individual.repaymentplan')</th>
									<th>@lang('individual.interestrate')</th>
									<th>@lang('individual.currency')</th>
									<th>@lang('individual.createdon')</th>
									<th>@lang('individual.action')</th>
								</tr>
							</thead>

							<tbody>
								@foreach($packages as $package)
									<tr>
										<td>{{ $package->name }}</td>
										<td>{{ $package->repayment_plan }}</td>
										<td>{{ $package->interest_rate }}</td>
										<td>{{ $package->currency }}</td>
										<td>{{ $package->created_at }}</td>
										<td>
											<a href="{{ route('user-packages.show', $package->id) }}"class="btn btn-info mr-1">View</a></a> &nbsp;
											<a href="{{ route('user-packages.edit', $package->id) }}" class="btn btn-dark mr-1">Edit</a></a> &nbsp;
											<a href="{{ route('user-packages.delete', $package->id) }}" class="btn btn-danger">Delete</a> &nbsp;
										</td>
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
