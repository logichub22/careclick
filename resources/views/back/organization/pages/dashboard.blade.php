@extends('back/organization/layouts/master')

@section('title')
	Organization Dashboard
@endsection

@section('content')
	<div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon l-bg-purple">
                    <i class="fas fa-money-check"></i>
                </div>
                <div class="card-wrap">
                    <div class="padding-20">
                        <div class="text-right">
                            <h4 class="font-light mb-0">
                            <sub>{{ $currency ? $currency : null }}</sub>
                                <i class="ti-arrow-up text-success"></i>
                                {{ number_format($wallet->balance) }}
                            </h4>
                            <span class="text-muted">@lang('layout.balance')</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon l-bg-orange">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="padding-20">
                        <div class="text-right">
                            <h3 class="font-light mb-0">
                                <i class="ti-arrow-up text-success"></i>
                                {{ count($users) }}
                            </h3>
                            <span class="text-muted">@lang('layout.users')</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon l-bg-cyan">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="card-wrap">
                    <div class="padding-20">
                        <div class="text-right">
                            <h3 class="font-light mb-0">
                                <i class="ti-arrow-up text-success"></i>
                                {{ count($groups) }}
                            </h3>
                            <span class="text-muted">@lang('layout.Groups')</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon l-bg-green">
                    <i class="fas fa-piggy-bank"></i>
                </div>
                <div class="card-wrap">
                    <div class="padding-20">
                        <div class="text-right">
                            <h3 class="font-light mb-0">
                                <sub>{{ $currency ? $currency : null }}</sub>
                                <i class="ti-arrow-up text-success"></i>
                                {{ number_format($savings_wallet->balance) }}
                            </h3>
                            <span class="text-muted">Savings</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    	<div class="col-md-3"></div>
    	<div class="col-md-6">
			<div class="card author-box">
	          <div class="card-body">
	            <div class="author-box-center">
	              <img alt="image" src="{{ asset('img/back/main/team.png') }}" class="rounded-circle author-box-picture">
	              <div class="clearfix"></div>
	              <div class="author-box-name">
	                <a href="#">{{ $org->name }}</a>
	              </div>
	            </div>
	            <div class="text-center">
	              <a href="{{ route('users.create') }}" class="btn btn-outline-primary btn-rounded mr-0-5">@lang('layout.newuser') <i class="ti-plus ml-0-5"></i></a>
					<a href="{{ route('groups.create') }}" class="btn btn-primary btn-rounded">@lang('layout.newgroup') <i class="ti-plus ml-0-5"></i></a>
	              <div class="w-100 d-sm-none"></div>
	            </div>
	          </div>
	        </div>
		</div>
		<div class="col-md-3"></div>
    </div>
@endsection
