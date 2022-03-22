@extends('back/individual/coopbank/layout')

@section('page-styles')
@endsection

@section('main-info')
  {{--  --}}
@endsection

@section('additional-card')

  @if ( isset($responseObj->Transactions) && count ($responseObj->Transactions) > 0 )
    <div class="card">
      <div class="card-body">
        <table class="table table-hover datatable">
          <thead>
            <tr>
              <th>Transaction ID</th>
              <th>Transaction Time</th>
              <th>Transaction</th>
              <th>Service Point</th>
              <th>Transaction Type</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($responseObj->Transactions as $transaction)
              <tr>
                <td>{{ $transaction->TransactionID }}</td>
                <td> {{ date("d-m-Y g:ia", strtotime($transaction->TransactionDate) ) }}</td>
                <td> {{ $transaction->Narration }}</td>
                <td> {{ $transaction->ServicePoint }} </td>
                @if ( $transaction->TransactionType == "C" )
                  <td>Credit</td>
                  <td>{{ $transaction->CreditAmount }} </td>
                @else
                  <td>Debit</td>
                  <td>{{ $transaction->DebitAmount }} </td>
                @endif
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    @section('page-styles')
      <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    @endsection
    
    @section('page-scripts')
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
    
  @endif

@endsection

@section('page-scripts')
@endsection