@extends('back/individual/layouts/master')

@section('title')
	Add a New Meeting
@endsection

@section('one-step')
    / Group Meeting
@endsection

@section('page-nav')
	<h4>New Group</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Groups</a></li>
		<li class="breadcrumb-item active">Add Meeting</li>
	</ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="box box-block bg-white">
                <h5 class="mb-1">A note on adding meetings</h5>
                <p>
                    1. Add a new group. For you to add a meeting, you must have a group that you own. If you already have a group, you can ignore this step.
                </p>
            </div>
        </div>
        <div class="col-md-8">
            <div class="box box-block bg-white">
                <h5 class="mb-1">Add a New Meeting</h5>
                <form action="" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="">Meeting Name</label>
                        <input type="text" placeholder="eg First Meeting" name="metting_name" class="form-control{{ $errors->has('meeting_name') ? ' is-inavlid' : ''  }}" value="{{ old('meeting_name') }}">
                        @if($errors->has('meeting_name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('meeting_name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Select Group</label>
                        <select name="group" id="" class="form-control{{ $errors->has('group') ? ' is-inavlid' : ' ' }}" >
                            <option value="" disabled selected>Please select group for this meeting</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('group'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('group') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Upload Minutes (If Any)</label>
                        <input type="file" name="minutes" id="" class="form-control{{ $errors->has('minutes') ? ' is-invalid' : '' }}">
                        @if($errors->has('minutes'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('minutes') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Venue</label>
                        <input type="text" placeholder="Your meeting venue" name="venue" class="form-control{{ $errors->has('venue') ? ' is-inavlid' : ''  }}" value="{{ old('venue') }}">
                        @if($errors->has('venue'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('venue') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Date</label>
                                <input type="date" placeholder="mm/dd/yyyy" name="date" class="form-control{{ $errors->has('date') ? ' is-inavlid' : ''  }}" value="{{ old('date') }}">
                                @if($errors->has('date'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
