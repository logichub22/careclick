@extends('back/trainer/layouts/master')

@section('title')
	Trainer Dashboard
@endsection

@section('content')
	@role('trainer')
		@include('back/trainer/pages/_trainer')
	@endrole
@endsection