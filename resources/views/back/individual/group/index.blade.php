@extends('back/individual/layouts/master')

@section('title')
	@lang('individual.mygroups')
@endsection

@section('one-step')
    / Groups
@endsection

@section('spec-styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
          href=" {{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
	<div class="row">
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h4 class="font-weight-light">@lang('individual.mygroups')</h4>
                    <div class="card-header-action">
                    	@if($user->hasRole('group-admin'))
                        <a href="{{ route('user-groups.create') }}" class="btn btn-danger btn-icon icon-right">Create Group
                        	<i class="fas fa-chevron-right"></i>
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
                            <thead>
								<tr>
									<th>@lang('individual.groupname')</th>
									<th>@lang('individual.comment')</th>
									<th>@lang('individual.status')</th>
									<th>@lang('individual.dateofcreation')</th>
									<th>@lang('individual.action')</th>
								</tr>
							</thead>
						<!-- Fake Data -->
							<tbody>
								@if(count($groups) > 0)
									@foreach($groups as $group)
										<tr>
											<td>{{ $group->name }}</td>
											<td>{{ $group->comment }}</td>
											<td>
												@if($group->status)
													@lang('individual.active')
												@else
													@lang('individual.inactive') 
												@endif
											</td>
											<td>{{ date('M j, Y', strtotime($group->created_at)) . ' at ' . date('H:i', strtotime($group->created_at)) }}</td>
											<td>
												<a href="{{ route('user-groups.show', $group->id) }}" class="btn btn-sm btn-primary" title="View Group">@lang('individual.viewgroup')</a> &nbsp;
												@if(Auth::user()->hasRole('group-admin'))
													<a href="{{ route('usergroupsettings', $group->id) }}" class="btn btn-sm btn-success" title="Group Settings">@lang('individual.settings')</a>
												@endif
											</td>
										</tr>
									@endforeach
									@elseif($user->hasRole('group-admin'))
										<tr>
											<td class="alert alert-warning align-center" colspan="6">@lang('individual.nogroupsavailable')! <a href="{{ route('user-groups.create') }}">@lang('individual.createonehere')</a></td>
										</tr>
									@endif
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