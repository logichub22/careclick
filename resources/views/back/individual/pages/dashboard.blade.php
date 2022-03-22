@extends('back/individual/layouts/master')

@section('title')
	@lang('individual.userdashboard')
@endsection

@section('content')
	<div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon l-bg-purple">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="card-wrap">
                    <div class="padding-20">
                        <div class="text-right">
                            <h3 class="font-light mb-0">
                                <sub>{{ $currency ? $currency->prefix : null }}</sub>
                                <i class="ti-arrow-up text-success"></i>
                                <span class="dashboard-balance">{{ number_format($wallet->balance) }}</span>
                            </h3>
                            <span class="text-muted">@lang('individual.balance')</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon l-bg-green">
                    <i class="fas fa-money-check"></i>
                </div>
                <div class="card-wrap">
                    <div class="padding-20">
                        <div class="text-right">
                            <h3 class="font-light mb-0">
                                <i class="ti-arrow-up text-success"></i>
                                {{ $loan_count }}
                            </h3>
                            <span class="text-muted">@lang('individual.loansborrowed')</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon l-bg-orange">
                    <i class="fas fa-piggy-bank"></i>
                </div>
                <div class="card-wrap">
                    <div class="padding-20">
                        <div class="text-right">
                            <h3 class="font-light mb-0">
                                <sub>{{ $currency ? $currency->prefix : null }}</sub>
                                <i class="ti-arrow-up text-success"></i>
                                <span class="dashboard-balance">{{ number_format($savings_wallet->balance) }}</span>
                            </h3>
                            <span class="text-muted">Savings</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon l-bg-cyan">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="card-wrap">
                    <div class="padding-20">
                        <div class="text-right">
                            <h3 class="font-light mb-0">
                                <i class="ti-arrow-up text-success"></i>
                                    {{ $group_count }}
                            </h3>
                            <span class="text-muted">@lang('individual.groups')</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-12">

            <div class="card">
                <div class="card-header">
                    <h4 class="font-weight-light">@lang('individual.latesttransactions')</h4>
                    <div class="card-header-action">
                        <a href="{{ route('user.transactions') }}" class="btn btn-danger btn-icon icon-right">View All <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
								<tr>
									<th>@lang('individual.transactioncode')</th>
									<th>@lang('individual.amount')</th>
									<th>@lang('individual.transactiondate')</th>
								</tr>
							</thead>
                           <tbody>
								@foreach($transactions as $transaction)
									<tr>
										<td>{{ $transaction->txn_code }}</td>
										<td>{{ $currency->prefix . ' ' . number_format($transaction->amount) }}</td>
										{{-- <td>
											@if($transaction->status)
												@lang('individual.successful')
											@else
												@lang('individual.failed')
											@endif
										</td> --}}
										<td>{{ $transaction->created_at }}</td>
									</tr>
								@endforeach
							</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">

            <div class="card">
                <div class="card-header">
                    <h4 class="font-weight-light">@lang('individual.myrecentloans')</h4>
                    <div class="card-header-action">
                        <a href="{{ route('user-loans.index') }}" class="btn btn-danger btn-icon icon-right">View All <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
							<tr>
								<th>@lang('individual.loanname')</th>
								<th>@lang('individual.amount')</th>
								<th>@lang('individual.dateborrowed')</th>
								<th>@lang('individual.action')</th>
							</tr>
						</thead>
						<tbody>
							@foreach($loans as $loan)
								<tr>
									<td>{{ $loan->loan_title }}</td>
									<td>{{ $currency->prefix . ' ' . number_format($loan->amount) }}</td>
									<td>{{ $loan->created_at }}</td>
									<td><a href="{{ route('user-loans.show', $loan->id) }}">View Loan</a></td>
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
