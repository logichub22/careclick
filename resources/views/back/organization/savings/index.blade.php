@extends('back/organization/layouts/master')

@section('title')
    Savings
@endsection

@section('one-step')
    / Savings
@endsection


@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-green">
                            <i class="fas fa-piggy-bank"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        <sub>{{ $currency }}</sub><br>
                                        <i class="ti-arrow-up text-success"></i>
                                        {{ number_format($wallet->balance) }}
                                    </h3>
                                    <span class="text-muted">Total Savings</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-purple">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        <sub>{{ $currency }}</sub><br>
                                        <i class="ti-arrow-up text-success"></i>
                                        0
                                    </h3>
                                    <span class="text-muted">Withdrawals</span>
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
                        <a href="{{ route('orgsavings-add-money') }}" class="btn btn-danger btn-icon icon-right">Deposit to Savings<i class="fas fa-chevron-right"></i></a>
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
                        <a href="{{ route('orgsavings-transfer') }}" class="btn btn-danger btn-icon icon-right">Transfer to Main Wallet<i class="fas fa-chevron-right"></i></a>
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
