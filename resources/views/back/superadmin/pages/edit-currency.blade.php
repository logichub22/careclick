@extends('back/superadmin/layouts/master')

@section('title')
	Edit Currency
@endsection

@section('page-nav')
	<h4>Edit Currency</h4>
	<ol class="breadcrumb no-bg mb-1">
        <li class="breadcrumb-item"><a href="{{ route('super.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('super.settings') }}">Manage Settings</a></li>
		<li class="breadcrumb-item active">Edit Currency</li>
	</ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="box box-block bg-white">
                <h5 class="mb-1">Edit {{ $currency->name }}</h5>
                <form action="{{ route('currencies.update', $currency->id) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="form-group">
                        <label for="name">Currency Name</label>
                        <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : ''}}" value="{{ $currency->name }}" name="name">
                        @if($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
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