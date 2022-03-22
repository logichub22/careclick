@extends('back/superadmin/layouts/master')

@section('title')
	System Configurations
@endsection

@section('one-step')
    / System Configurations
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
                    <h5 class="mb-1">Add a new message</h5>
                    <form action="{{ route('configs.store') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">Message Name</label>
                        <input type="text" class="form-control" placeholder="Message Title" name="name" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="" cols="10" rows="5" class="form-control" placeholder="Description">{{ old('name') }}</textarea>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-block" type="submit">Save Message</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-md-9">
             <div class="card">
                <div class="card-header">
                    <h4 class="mb-1">Available system messages</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
                            <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $message)
                            <tr>
                                <td>{{ $message->name }}</td>
                                <td>{{ $message->description }}</td>
                                <td>
                                    @if($message->status)
                                        Active
                                    @else
                                        Inactive
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('configs.edit', $message->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i></a> &nbsp;
                                    {!! Form::open(['route' => ['configs.destroy', $message->id], 'method' => 'DELETE', 'style' => 'display: inline-block', 'id' => 'deleteForm']) !!}                                
                                    {{Form::button('<i class="fas fa-trash"></i>', array('type' => 'submit', 'class' => 'btn btn-sm btn-danger', 'title' => 'Delete', 'id' => 'deleteButton'))}}
                                    {!! Form::close() !!}
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
    <script>
        $(document).ready(function() {
            // Cancel Membership
            $('#deleteButton').click(function(event) {
                event.preventDefault();
                swal({
                  title: "Are you sure?",
                  text: "By clicking OK, this system message will be deleted",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willDelete) => {
                  if (willDelete) {
                    $('#deleteForm').submit();
                  } else {
                 //    swal({
                    //   title: "Are you sure?",
                    //   text: "By clicking OK, " + name + "'s membership will be cancelled",
                    //   icon: "warning",
                    //   buttons: true,
                    //   dangerMode: true,
                    // })
                  }
                });   
            });
        })
    </script>


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