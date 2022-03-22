@extends('back/organization/layouts/master')

@section('title')
	CO-OPBank
@endsection

@section('spec-styles')
  @yield('page-styles')    
@endsection

@section('one-step')
    / CO-OPBank
@endsection

@section('content')
  <style>
    .main-info th {
      width: 25%;
    }
  </style>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4>{{ $page['title'] }}</h4>
          <a href="{{ route('coopbank') }}" class="btn btn-primary ml-auto"><i class="fa fa-chevron-circle-left"></i> Back</a>
        </div>
        <div class="card-body">
          @if(isset($responseObj->fault))
          <p>Error: {{ $responseObj->fault->message }}</p>
          @else
            @if ($page['action'] != "status" && $responseObj->MessageCode == 0)
              <table class="table main-info table-hover">
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

                  @elseif (in_array($page['action'], ["mini_statement", "statement", "transactions"]))
                    <tr>
                      <th>Number of Transactions</th>
                      <td>{{ count($responseObj->Transactions) }}</td>
                    </tr>

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

                  @endif
                </tbody>
              </table>
              
            @elseif ($page['action'] == "status")
              @if($responseObj->messageCode == 0)
                <table class="table main-info table-hover">
                  <tbody>
                    <tr>
                      <th>Messaage Reference</th>
                      <td>{{ $responseObj->messageReference }}</td>
                    </tr>
                    <tr>
                      <th>Transaction ID</th>
                      <td>{{ $responseObj->destination->transactionID }}</td>
                    </tr>
                    <tr>
                      <th>Source Account</th>
                      <td>{{ $responseObj->source->accountNumber }}</td>
                    </tr>
                    <tr>
                      <th>Destination Account</th>
                      <td>{{ $responseObj->destination->accountNumber }}</td>
                    </tr>
                    <tr>
                      <th>Amount</th>
                      <td>{{ $responseObj->destination->transactionCurrency . " " . $responseObj->destination->amount }}</td>
                    </tr>
                    <tr>
                      <th>Narration</th>
                      <td>{{ $responseObj->destination->narration }}</td>
                    </tr>
                    <tr>
                      <th>Reference Number</th>
                      <td>{{ $responseObj->destination->accountNumber }}</td>
                    </tr>
                    <tr>
                      <th>Transaction Status</th>
                      <td>{{ $responseObj->destination->responseDescription }}</td>
                    </tr>
                  </tbody>
                </table>
              @else
                <div class="alert alert-info">Warning...</div>
              @endif
              
            @else
              <div class="alert alert-warning">Error: {{ $responseObj->MessageDescription }}</div>
            @endif

          @endif
        </div>
        {{-- <div class="card-footer">
          <a href="{{ route('coopbank') }}" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Back</a>
        </div> --}}
      </div>

      @yield('additional-card')
    </div>
  </div>
@endsection

@section('spec-scripts')
  @yield('page-scripts')
@endsection

