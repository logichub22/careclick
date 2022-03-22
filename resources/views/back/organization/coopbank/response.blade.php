@extends('back/organization/layouts/master')


@section('title')
	CO-OPBank
@endsection

@section('one-step')
    / CO-OPBank
@endsection

@if (in_array($page['action'], ["mini_statement", "statement", "transactions"]))
  @section('spec-styles')
      <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
  @endsection
@endif

@section('content')
  <style>
    .table th {
      width: 25%;
    }
  </style>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
        <div class="card-header">
          <h4>{{ $page['title'] }}</h4>
        </div>
        <div class="card-body">
          @if(isset($responseObj->fault))
          <p>Error: {{ $responseObj->fault->message }}</p>
          @else

            @if ($responseObj->MessageCode == 0)
              <table class="table table-hover">
                <tbody>
                  @if (isset($responseObj->AccountName))
                  <tr>
                    <th>Name</th>
                    <td>{{ $responseObj->AccountName }}</td>
                  </tr>
                  @endif
                  @if (isset($responseObj->AccountNumber))
                  <tr>
                    <th>Account Number</th>
                    <td>{{ $responseObj->AccountNumber }}</td>
                  </tr>
                  @endif
                  {{-- <tr>
                    <th>Account Type</th>
                    <td>{{ $responseObj->ProductName }}</td>
                  </tr> --}}

                  @if ($page['action'] == "balance")
                  <tr>
                    <th>Account Balance</th>
                    <td>{{ "{$responseObj->Currency} {$responseObj->AvailableBalance}" }}</td>
                  </tr>

                  @elseif ($page['action'] == "transactions")
                    <tr>
                      <th>Number of Transactions</th>
                      <td>{{ count($responseObj->Transactions) }}</td>
                    </tr>
                    @if ( count ($responseObj->Transactions) > 0)
                      {{-- Close Card and Open a new one --}}
                      </tbody></table> {{-- Close current table & Open a new one --}}
                      </div></div><div class="card"><div class="card-body">
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
                                  <th>{{ $transaction->TransactionID }}</th>
                                  <th> {{ date("d-m-Y g:ia", strtotime($transaction->TransactionDate) ) }}</th>
                                  <th> {{ $transaction->Narration }}</th>
                                  <th> {{ $transaction->ServicePoint }} </th>
                                  @if ( $transaction->TransactionType == "C" )
                                    <th>Credit</th>
                                    <th>{{ $transaction->CreditAmount }} </th>
                                  @else
                                    <th>Debit</th>
                                    <th>{{ $transaction->DebitAmount }} </th>
                                    {{ $transaction->CreditAmount }}   
                                  @endif
                                </tr>
                            @endforeach
                    @endif

                  

                  @elseif (in_array($page['action'], ["mini_statement", "statement"]))
                    <tr>
                      <th>Number of Transactions</th>
                      <td>{{ count($responseObj->Transactions) }}</td>
                    </tr>
                    @if ( count ($responseObj->Transactions) > 0)
                      {{-- Close Card and Open a new one --}}
                      </tbody></table> {{-- Close current table & Open a new one --}}
                      </div></div><div class="card"><div class="card-body">

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
                                  <th>{{ $transaction->TransactionID }}</th>
                                  <th> {{ date("d-m-Y g:ia", strtotime($transaction->TransactionDate) ) }}</th>
                                  <th> {{ $transaction->Narration }}</th>
                                  <th> {{ $transaction->ServicePoint }} </th>
                                  @if ( $transaction->TransactionType == "C" )
                                    <th>Credit</th>
                                    <th>{{ $transaction->CreditAmount }} </th>
                                  @else
                                    <th>Debit</th>
                                    <th>{{ $transaction->DebitAmount }} </th>
                                    {{ $transaction->CreditAmount }}   
                                  @endif
                                </tr>
                            @endforeach
                    @endif

                  

                  @elseif ($page['action'] == "validation")
                  <tr>
                    <th>Validation Status</th>
                    <td>{{ $responseObj->MessageDescription }}</td>
                  </tr>
                  
                  @elseif ($page['action'] == "exchange")
                  <tr>
                    <th>Conversion Rate</th>
                    <td>
                      {{ "1 {$responseObj->FromCurrencyCode} = " }}
                      @if($responseObj->MultiplyDivide == "M")
                      {{1 * $responseObj->Rate }}
                      @else
                      {{ 1 / $responseObj->Rate }}
                      @endif
                      {{ " {$responseObj->ToCurrencyCode}" }}
                    </td>
                  </tr>
                  
                  @elseif (in_array($page['action'], ["iftransfer", "pesalink_transfer", "pesalink_phone", "mpesa"]))
                  <tr>
                    <th>Transaction Status</th>
                    <td>{{ $responseObj->MessageDescription }}</td>
                  </tr>

                  @else
                      {{--  --}}
                  @endif
                </tbody>
              </table>
            @else
              <div class="alert alert-warning">Error: {{ $responseObj->MessageDescription }}</div>
            @endif

          @endif
        </div>
        <div class="card-footer">
          <a href="{{ route('coopbank') }}" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Back</a>
        </div>
      </div>
		</div>
  </div>
@endsection

@section('spec-scripts')
    <script>
      $(function () {
        
      });
    </script>
@endsection

@if (in_array($page['action'], ["mini_statement", "statement"]))
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
@endif