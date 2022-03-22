@extends('back/superadmin/layouts/master')

@section('title')
	Manage Settings
@endsection

@push('styles')
    <style>
        .hidden{
            display: none;
        }
    </style>
@endpush

@section('page-nav')
	<h4>Manage Settings</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('super.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item active">Manage Settings</li>
	</ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-block bg-white">
                <h5 class="mb-1">Currency Settings
                    <button class="btn btn-sm btn-primary" data-target="#addNew" data-toggle="modal"><i class="fas fa-plus"></i> Add new currency</button>
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="myTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                {{-- <th>Symbol</th>
                                <th>Exchange Rate (Against Ksh)</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($currencies as $currency)
                                <tr id="item{{ $currency->id }}">
                                    <td>{{ $currency->name }}</td>
                                    <td>
                                        <a href="{{ route('currencies.edit', $currency->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-pencil-alt"></i></a> &nbsp; 
                                        {!! Form::open(['route' => ['currencies.destroy', $currency->id], 'method' => 'POST', 'style' => 'display: inline-block', 'id' => 'deleteForm']) !!}                               
                                            {{Form::button('<i class="fa fa-trash"></i>', array('type' => 'submit', 'class' => 'btn btn-sm btn-danger', 'title' => 'Delete', 'id' => 'delete'))}}
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

    <div class="row">
        <div class="col-md-12">
            <div class="box box-block bg-white">
                <h5 class="mb-1">Group Settings</h5>
                <hr>
                <h5 class="mb-1">Available Settings</h5>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h6 class="modal-title">Add a new currency</h6>
                    </div>
                    <form action="{{ route('currencies.store') }}" method="POST" id="addForm">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="error text-center alert alert-danger hidden">
                                
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-control-label">Name:</label>
                                <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : ''}}" name="name" value="{{ old('currency') }}" placeholder="Currency Name" id="name">
                                @if($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close">Close</button>
                            <button type="submit" class="btn btn-primary" id="add">Add Currency</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#delete').click(function(event) {
				event.preventDefault();
                console.log("Hey");
				swal({
				  title: "Are you sure?",
				  text: "By clicking OK, this currency will be deleted",
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
        });
    </script>
@endpush