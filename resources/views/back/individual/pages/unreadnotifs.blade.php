@extends('back/individual/layouts/master')

@section('title')
	@lang('individual.unreadnotifications')
@endsection

@section('page-nav')
	<h4>@lang('individual.loans')</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('individual.home')</a></li>
		<li class="breadcrumb-item active">@lang('individual.unreadnotifications')</li>
	</ol>
@endsection

@section('content')
	<div class="box box-block bg-white">
		<h5 class="mb-1">@lang('individual.allunreadnotifications')</h5>
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered table-2">
				<thead>
                    <tr>
                        <th>@lang('individual.message')</th>
                        <th>@lang('individual.deliveredon')</th>
                        <th>@lang('individual.actions')</th>
                    </tr>
                </thead>
				<tbody>
					@foreach($notifications as $notification)
						<tr>
							<td>{{ $notification->data['data'] }}</td>
							<td>
								{{ date('M j, Y', strtotime($notification->created_at)) . ' at ' . date('H:i', strtotime($notification->created_at)) }}
                            </td>
                            <td>
                                <a href="" class="btn btn-primary btn-sm" title="Mark as read"><i class="fas fa-eye"></i></a> &nbsp;
                                <a href="" class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></a> &nbsp;
                            </td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection