@extends('back/superadmin/layouts/master')

@section('title')
	Edit Message
@endsection

@section('one-step')
    / Edit System Configuration
@endsection

@section('page-nav')
	<h4>Configs</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('super.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="{{ route('configs.index') }}">System Configs</a></li>
	</ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-1">Edit Message</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('configs.update', $message->id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        <div class="form-group">
                            <label for="name">Message Name</label>
                            <input type="text" class="form-control" placeholder="Message Title" name="name" value="{{ $message->name }}">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="" cols="10" rows="5" class="form-control" placeholder="Description">{{ $message->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-block" type="submit">Update Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection