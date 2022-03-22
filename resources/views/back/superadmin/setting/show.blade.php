@extends('back/superadmin/layouts/master')

@section('title')
	Manage Setting
@endsection

@section('page-nav')
	<h4>Manage Setting</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('super.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
        <li class="breadcrumb-item active">Configure</li>
	</ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="box box-block bg-white">
                <h5 class="mb-1">Setting Details</h5>
                <p>
                    Name: {{ $setting->name }}
                </p>
                <p>
                    Description: {{ $setting->description }}
                </p>
                <p>
                    Status: 
                    @if($setting->status)
                        Active
                    @else
                        Inactive
                    @endif
				</p>
				<p>
					Category:
					{{ $name }}
				</p>
                <p>
                    @if($setting->status)
                        {{-- <a href="#" class="btn btn-danger btn-block" id="cancel">Cancel Membership</a> --}}
                        {!! Form::open(['route' => ['settings.deactivate', $setting->id],'method' => 'POST', 'style' => 'display: inline-block', 'id' => 'cancelForm']) !!}                             
                          {{Form::button('Deactivate', array('id'=> 'cancel', 'class' => 'btn btn-block btn-warning', 'title' => 'Deactivate'))}}
                       {!! Form::close() !!}
                   @else
                       {!! Form::open(['route' => ['settings.activate', $setting->id],'method' => 'POST', 'style' => 'display: inline-block', 'id' => 'renewForm']) !!}                               
                          {{Form::button('Activate', array('id'=> 'renew', 'class' => 'btn btn-block btn-primary', 'title' => 'Activate'))}}
                       {!! Form::close() !!}
                   @endif
                </p>
            </div>
        </div>
        <div class="col-md-8">
            <div class="box box-block bg-white">
                <h5 class="mb-1">Update</h5>
                <form action="{{ route('settings.update', $setting->id) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="form-group">
                        <label for="name" class="form-control-label">Name:</label>
                        <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : ''}}" name="name" value="{{ $setting->name }}" placeholder="Setting Name">
                        @if($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="form-control-label">Description:</label>
                        <textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" placeholder="Description">{{ $setting->description }}</textarea>
                        @if($errors->has('description'))
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script>
		$(document).ready(function() {
			// Cancel Membership
			$('#cancel').click(function(event) {
				event.preventDefault();
				var name = $('#name').val();
				swal({
				  title: "Are you sure?",
				  text: "By clicking OK, this setting will be deactivated",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				    $('#cancelForm').submit();
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

			// Renew Membership
			$('#renew').click(function(event) {
				event.preventDefault();
				var name = $('#name').val();
				swal({
				  title: "Are you sure?",
				  text: "By clicking OK, " + name + "'s membership will be renewed",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				    $('#renewForm').submit();
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

			// Delete Membership
			$('#delete').click(function(event) {
				event.preventDefault();
				var name = $('#name').val();
				swal({
				  title: "Are you sure?",
				  text: "By clicking OK, " + name + " will be deleted from this group",
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
@endpush