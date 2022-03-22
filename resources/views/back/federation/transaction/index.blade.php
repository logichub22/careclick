@extends('back/organization/layouts/master')

@section('title')
	Transactions
@endsection

@section('page-nav')
	<h4>My Transactions</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a>Transactions</a></li>
        <li class="breadcrumb-item active"><a>All</a></li>
	</ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('org.ledger') }}" class="btn btn-primary">View Ledger</a>
            <div class="box box-block bg-white">
                <h5 class="mb-1">All Transactions</h5>
                <table class="table table-hover table-bordered table-2 table-striped">
                    <thead>
                        <tr>
                            <th>Trans Code</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->txn_code }}</td>
                                <td>{{ number_format($transaction->amount) }}</td>
                                <td>
                                    @if($transaction->txn_type == 1)
                                        Credit
                                    @else
                                        Debit
                                    @endif
                                </td>
                                <td>{{ $transaction->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection