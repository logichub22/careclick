@extends('back/individual/layouts/master')

@section('title')
	@lang('individual.createnewgroup')
@endsection

@section('one-step')
    / Group / Create 
@endsection


@section('content')
	<div class="row">
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h4>@lang('individual.newgroup')</h4>
                </div>
                <div class="card-body">
                	@if ($errors->any())
				        <div class="alert alert-danger">
				            <ul>
				                @foreach ($errors->all() as $error)
				                    <li>{{ $error }}</li>
				                @endforeach
				            </ul>
				        </div>
				    @endif
                	<form action="{{ route('user-groups.store') }}" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<input type="hidden" value="{{ $user->id }}" name="user_id">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                           <label for="name">@lang('individual.groupname') <span class="important">*</span></label>
								<input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Name of your group" value="{{ old('name') }}" maxlength="100">
								@if($errors->has('name'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('name') }}</strong>
									</span>
								@endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="name">@lang('individual.association')</label>
								<select name="association" class="form-control{{ $errors->has('association') ? ' is-invalid' : '' }}" id="">
									<option value="" disabled selected>@lang('individual.selectassociation')</option>
									@foreach($associations as $association)
										<option value="{{ $association->id }}">{{ $association->name }}</option>
									@endforeach
								</select>
								@if($errors->has('association'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('association') }}</strong>
									</span>
								@endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">@lang('individual.groupdocument')</label>
								<input type="file" name="group_certificate" class="form-control{{ $errors->has('group_certificate') ? ' is-invalid' : '' }}">
								@if ($errors->has('group_certificate'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('group_certificate') }}</strong>
									</span>
								@endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">@lang('individual.doyouhaveabankaccount')? <span class="important">*</span></label>
								<select name="bank" class="form-control{{ $errors->has('bank') ? ' is-invalid' : '' }}" id="bank_select">
									<option value="" disabled selected>@lang('individual.doyouhaveabankaccount')?</option>
									<option value="1">@lang('individual.yes')</option>
									<option value="0">@lang('individual.no')</option>
								</select>
								@if ($errors->has('bank'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('bank') }}</strong>
									</span>
								@endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">@lang('individual.bankname') <span class="important">*</span></label>
								<input type="text" name="bank_name" class="form-control{{ $errors->has('bank_name') ? ' is-invalid' : '' }}" placeholder="Name of your bank" value="{{ old('bank_name') }}" maxlength="100" id="bank_name">
								@if($errors->has('bank_name'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('bank_name') }}</strong>
									</span>
								@endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">@lang('individual.branch') <span class="important">*</span></label>
								<input type="text" name="bank_branch" class="form-control{{ $errors->has('bank_branch') ? ' is-invalid' : '' }}" placeholder="Branch" value="{{ old('bank_branch') }}" maxlength="100" id="bank_branch">
								@if($errors->has('bank_branch'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('bank_branch') }}</strong>
									</span>
								@endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="account_no">@lang('individual.accountnumber') <span class="important">*</span></label>
							<input type="text" name="account_no" class="form-control{{ $errors->has('account_no') ? ' is-invalid' : '' }}" placeholder="Account Number" value="{{ old('account_no') }}" maxlength="100" id="account_no">
							@if($errors->has('account_no'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('account_no') }}</strong>
								</span>
							@endif
                        </div>
                        <div class="row">
						@if(count($regions_arr) === 4)
							@foreach($regions_arr as $key => $value)
								<div class="col-md-3">
									<label for="">{{ $value }}</label>
									@if($key == 0)
										<select name="level_one" id="levelOneID" class="form-control{{ $errors->has('level_one') ? ' is-invalid' : '' }}">
											<option value="" disabled selected>@lang('individual.select') {{ $value }}</option>
											@foreach($level_ones as $level_one)
												<option value="{{ $level_one->id }}">{{ $level_one->name }}</option>
											@endforeach
										</select>
										@if ($errors->has('level_one'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('level_one') }}</strong>
											</span>
										@endif
									@elseif($key == 1)
										<select name="level_two" id="levelTwoID" class="form-control{{ $errors->has('level_two') ? ' is-invalid' : '' }}">
											<option value="" disabled selected>Select {{ $value }}</option>

										</select>
										@if ($errors->has('level_two'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('level_two') }}</strong>
											</span>
										@endif
									@elseif($key == 2)
										<select name="level_three" id="levelThreeID" class="form-control{{ $errors->has('level_three') ? ' is-invalid' : '' }}">
											<option value="" disabled selected>Select {{ $value }}</option>

										</select>
										@if ($errors->has('level_three'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('level_three') }}</strong>
											</span>
										@endif
									@elseif($key == 3)
										<input type="text" class="form-control{{ $errors->has('level_four') ? ' is-invalid' : '' }}" name="level_four" placeholder="{{ $value }}">
										@if ($errors->has('level_four'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('level_four') }}</strong>
											</span>
										@endif
									@endif
								</div>
							@endforeach
						@elseif(count($regions_arr) === 3)
							@foreach($regions_arr as $key => $value)
								<div class="col-md-4">
									<label for="">{{ $value }}</label>
									@if($key == 0)
										<select name="level_one" id="levelOneID" class="form-control{{ $errors->has('level_one') ? ' is-invalid' : '' }}">
											<option value="" disabled selected>@lang('individual.select') {{ $value }}</option>
											@foreach($level_ones as $level_one)
												<option value="{{ $level_one->id }}">{{ $level_one->name }}</option>
											@endforeach
										</select>
										@if ($errors->has('level_one'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('level_one') }}</strong>
											</span>
										@endif
									@elseif($key == 1)
										<select name="level_two" id="levelTwoID" class="form-control{{ $errors->has('level_two') ? ' is-invalid' : '' }}">
											<option value="" disabled selected>Select {{ $value }}</option>

										</select>
										@if ($errors->has('level_two'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('level_two') }}</strong>
											</span>
										@endif
									@elseif($key == 2)
										<select name="level_three" id="levelThreeID" class="form-control{{ $errors->has('level_three') ? ' is-invalid' : '' }}">
											<option value="" disabled selected>Select {{ $value }}</option>

										</select>
										@if ($errors->has('level_three'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('level_three') }}</strong>
											</span>
										@endif
									@endif
								</div>
							@endforeach
						@elseif(count($regions_arr) == 2)
							@foreach ($regions_arr as $key => $value)
							<div class="col-md-6">
									<label for="">{{ $value }}</label>
									@if($key == 0)
										<select name="level_one" id="levelOneID" class="form-control{{ $errors->has('level_one') ? ' is-invalid' : '' }}">
											<option value="" disabled selected>Select {{ $value }}</option>

										</select>
										@if ($errors->has('level_one'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('level_one') }}</strong>
											</span>
										@endif
									@elseif($key == 1)
										<select name="level_two" id="levelTwoID" class="form-control{{ $errors->has('level_two') ? ' is-invalid' : '' }}">
											<option value="" disabled selected>@lang('individual.select') {{ $value }}</option>

										</select>
										@if ($errors->has('level_two'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('level_two') }}</strong>
											</span>
										@endif
									@endif
								</div>
							@endforeach
						@endif
					</div>
					<br />
					<div class="col-md-12">
						<div class="form-group col-md-12">
							<label for="comment">@lang('individual.comment') <span class="important">*</span></label>
							<textarea name="comment" class="form-control{{ $errors->has('comment') ? ' is-invalid' : '' }}" rows="3" placeholder="@lang('individual.additionalcomments')">{{ old('comment') }}</textarea>
							@if($errors->has('comment'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('comment') }}</strong>
								</span>
							@endif
						</div>
					</div>
					<div class="card-footer form-group">
						<button type="submit" class="btn btn-primary btn-block">@lang('individual.creategroup')</button>
					</div>
				</form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('spec-scripts')
	<script>
		$(document).ready(function () {
			if (localStorage.getItem("bank") == 1) {
				$('#bank_select').val(1);
				$('#bank_row').show();
			} else if(localStorage.getItem("bank") == 0) {
				$('#bank_select').val(0);
				$('#bank_row').css("display", "none");
				$('#bank_name').val();
				$('#bank_branch').val();
				$('#account_no').val();
			}

			$('#bank_select').on('change', function () {
				localStorage.setItem("bank", $(this).val());
				if ($(this).val() == 1) {
					$('#bank_row').show();
				} else {
					$('#bank_row').hide();
					$('#bank_name').val();
					$('#bank_branch').val();
					$('#account_no').val();
				}
			});

			$('#levelOneID').on('change', function(e){
				//console.log(e);
				var level_id = e.target.value;
				$.get('{{ url('load/level_two/') }}'+'/'+level_id, function() {
					//alert( "success" );
				})
				.done(function( data ) {
				//alert( "Data Loaded:");
				$('#levelTwoID').empty();
					if (Object.keys(data).length > 0) {
						$.each(data, function(key, value) {
							$('#levelTwoID').append('<option value="'+key+'">'+ value +'</option>');
						});
					} else {
						$('#levelTwoID').append('<option value="" disabled selected>No data found</option>')
					}

				})
				.fail(function(error) {
					console.log(JSON.stringify(error));
				});
			});

			$('#levelTwoID').on('change', function(e){
				//console.log(e);
				var level_id = e.target.value;

				$.get('{{ url('load/level_three/') }}'+'/'+level_id, function() {
					//alert( "success" );
				})
				.done(function( data ) {
				//alert( "Data Loaded:");
				$('#levelThreeID').empty();
					if (Object.keys(data).length > 0) {
						$.each(data, function(key, value) {
							$('#levelThreeID').append('<option value="'+key+'">'+ value +'</option>');
						});
					} else {
						$('#levelThreeID').append('<option value="" disabled selected>No data found</option>')
					}

				})
				.fail(function(error) {
					console.log(JSON.stringify(error));
				});
			});
		});
	</script>
@endsection
