@extends('back/superadmin/layouts/master')

@section('title')
	View Group
@endsection

@section('one-step')
    / Group Details
@endsection

@section('content')
	@section('spec-styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-4 col-md-3">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-4">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa fa-home"></i> {{ $group->name }} 
                            </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="far fa-money-bill-alt"></i> Balance: &nbsp;
                            {{ number_format($wallet->balance) }}
                        </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa fa-users"></i> Members : &nbsp;
                                {{ count($members) }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="far fa-calendar-alt"></i> Created {{ date('M j, Y', strtotime($group->created_at)) . ' at ' . date('H:i', strtotime($group->created_at)) }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="far fa-calendar-alt"></i> Admin Name: {{ $admin->name . ' ' . $admin->other_names }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="far fa-calendar-alt"></i> Admin Phone: {{ $admin->msisdn }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="far fa-calendar-alt"></i> Admin Email: {{ $admin->email }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-md-9">
             <div class="card">
                <div class="card-header">
                    <h4 class="mb-1">Group Members</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
                            <thead>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Account Number</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach ($members as $member)
                                    <tr>
                                        <td>{{ $member->name . ' ' . $member->other_names }}</td>
                                        <td>{{ $member->email }}</td>
                                        <td>{{ $member->msisdn }}</td>
                                        <td>
                                            @if(is_null($member->account_no))
                                                Not Set
                                            @else
                                                {{ $member->account_no }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('all-users.show', $member->id) }}" class="btn btn-sm btn-primary" title="View Member">View</i></a> &nbsp;
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
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

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