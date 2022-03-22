@extends('back/organization/layouts/master')

@section('title')
	Organization Wallet
@endsection

@section('one-step')
    / Wallet
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<div class="row">
				<div class="col-lg-4 col-md-6 col-sm-6 col-12">
		            <div class="card card-statistic-1">
		                <div class="card-icon l-bg-green">
		                    <i class="fas fa-money-bill-alt"></i>
		                </div>
		                <div class="card-wrap">
		                    <div class="padding-20">
		                        <div class="text-right">
		                            <h3 class="font-light mb-0">
		                                <i class="ti-arrow-up text-success"></i>
		                                {{ $bal }}
		                            </h3>
		                            <span class="text-muted">Balance</span>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
				<div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon l-bg-purple">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="card-wrap">
                    <div class="padding-20">
                        <div class="text-right">
                            <h3 class="font-light mb-0">
                                <i class="ti-arrow-up text-success"></i>
                                0
                            </h3>
                            <span class="text-muted">Withdrawals</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon l-bg-cyan">
                    <i class="fas fa-piggy-bank"></i>
                </div>
                <div class="card-wrap">
                    <div class="padding-20">
                        <div class="text-right">
                            <h3 class="font-light mb-0">
                                <i class="ti-arrow-up text-success"></i>
                                0
                            </h3>
                            <span class="text-muted">Deposits</span>
                        </div>
                    </div>
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
                    <h4 class="font-weight-light">All Your Deposits</h4>
                    <div class="card-header-action">
                        <a href="{{ route('org-add-money') }}" class="btn btn-danger btn-icon icon-right">Add Money<i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
                <div class="card-body">
                <div class="table-responsive">
                <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Transacction Date</th>
                            <th>Payment Method</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">

            <div class="card">
                <div class="card-header">
                    <h4 class="font-weight-light">All Your Withdrawals</h4>
                    <div class="card-header-action">
                        <a href="{{ route('org.withdraw-money') }}" class="btn btn-danger btn-icon icon-right">Withdraw<i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
                            <thead>
	                            <tr>
	                                <th>Amount</th>
	                                <th>Transacction Date</th>
	                                <th>Payment Method</th>
	                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection