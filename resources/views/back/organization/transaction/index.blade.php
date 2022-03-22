@extends('back/organization/layouts/master')

@section('title')
	@lang('layout.transactions')
@endsection

@section('one-step')
    / Transactions
@endsection

@section('spec-styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="w-100">
            <!-- <a href="{{ route('org.ledger') }}" class="btn btn-primary">@lang('layout.viewledger')</a> -->
            <div class="card">
                <div class="card-header">
                    <h4>@lang('layout.alltransactions')</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>@lang('layout.transcode')</th>
                                    <th>@lang('layout.amount')</th>
                                    <th>@lang('layout.type')</th>
                                    <th>@lang('layout.date')</th>
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
        </div>
    </div>
@endsection

@section('spec-scripts')
    <script src="{{ asset('assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/datatables.js') }}"></script>
@endsection
