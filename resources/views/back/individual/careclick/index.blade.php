@extends('back/individual/layouts/master')

@section('title')
	CareClick
@endsection

@section('one-step')
    / CareClick
@endsection
    
@section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- <a href="{{ route('org.ledger') }}" class="btn btn-primary">View Ledger</a> -->
        </div>
    </div>
    <div class="row">
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h4>All Transactions</h4>
                </div>
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
@endsection
