@extends('back/organization/layouts/master')

@section('title')
	Group Message
@endsection

@push('styles')
	<style>
		.important{
			color: red;
		}
	</style>
@endpush

@section('page-nav')
	<h4>Send a Group Message</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Groups</a></li>
		<li class="breadcrumb-item"><a href="{{ route('groups.index') }}">My Groups</a></li>
        <li class="breadcrumb-item"><a href="">{{ $group->name }}</a></li>
        <li class="breadcrumb-item active">Group Message</li>
	</ol>
@endsection

@section('content')
	<form action="{{ route('org.sendbulkemail') }}" method="POST" enctype="multipart/form-data">
		{{ csrf_field() }}
		<div class="row">
			<div class="col-md-6">
				<div class="box box-block bg-white">
					<h5 class="mb-1">Write your message</h5>
					<input type="hidden" name="group" value="{{ $group->name }}">
					<div class="form-group">
						<label for="subject">Subject <span class="important">*</span></label>
						<input type="text" name="subject" placeholder="Email Subject" class="form-control" required>
					</div>
					<div class="form-group">
						<label for="subject">Message <span class="important">*</span></label>
						<textarea name="message" id="message" cols="30" rows="10" class="form-control" placeholder="Group message" required></textarea>
					</div>
					{{-- <div class="form-group">
						<label for="attachment">Attachment</label>
						<input type="file" name="attachment" class="form-control">
					</div> --}}
				</div>
			</div>
			<div class="col-md-6">
				<div class="box box-block bg-white">
					<h5 class="mb-1">Recipients</h5>
					<button type="button" id="check_all" class="btn btn-primary" {{ $members->count() === 0 ? ' disabled' : '' }}>Select / Deselect All</button>
					<div class="form-group">
						<label for="recipients" class="pt-2 pb-1">Select Recipients</label>
						<div class="row">
							@if (count($members) > 0)
								@foreach ($members->chunk(3) as $chunk)
									@foreach ($chunk as $user)
										<div class="col-md-4">
											<input type="checkbox" name="recipients[]" value="{{ $user->id }}" class="recNames"> {{ $user->name . ' ' . $user->other_names }}
										</div>
									@endforeach
								@endforeach
							@else
								<div class="col-md-12">
									<div class="form-group alert alert-warning">
										Your group has no members yet
									</div>
								</div>
							@endif
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-8 offset-md-2">
								<button class="btn btn-primary btn-block" type="submit" {{ $members->count() === 0 ? ' disabled' : '' }}>Send Message</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
@endsection

@push('scripts')
	<script>
		//$(document).ready(function () {
			$('#check_all').click(function() {
				$('.recNames').prop('checked', true);
			});

			$('#check_all').click(function() {
				$('.recNames').prop('checked', false);
			});
		//})
	</script>
@endpush