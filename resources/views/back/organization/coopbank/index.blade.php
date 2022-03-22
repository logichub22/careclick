@extends('back/organization/layouts/master')


@section('title')
	CO-OPBank
@endsection

@section('one-step')
    / CO-OPBank
@endsection

@section('spec-styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
	<div class="row">
		<div class="col-md-4">
			<div class="card">
				<h5 class="card-header">Test Details</h5>
				<div class="accordion card-body" id="accordionExample">
				  <div class="card">
					    <div class="card-header" id="headingOne">
					      <h5 class="mb-0">
					        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#creditScore" aria-expanded="true" aria-controls="creditScore">
					          About API
					        </button>
					      </h5>
					    </div>

					    <div id="creditScore" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
					      <div class="card-body text-justify" style="padding: 15px;">
					        This page enables users to make test requests to the Co-opBank API endpoints. Select an option from the dropdown menu to begin. The account you can choose from some of the valid account numbers in the list, where applicable. 
					      </div>
					    </div>
				  </div>
				  <div class="card">
					    <div class="card-header" id="headingTwo">
					      <h5 class="mb-0">
					        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#interestRate" aria-expanded="true" aria-controls="interestRate">
					          Input Values
					        </button>
					      </h5>
					    </div>

					    <div id="interestRate" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
					      <div class="card-body text-justify" style="padding: 15px;">
                  <p>Some input fields have values that are constant, and remain <b>read-only</b>. The <b>Account Number</b> field must be 14 digits, and the API only authorizes specific account numbers. You can select a valid account number from the <i>datalist</i>. The last item on the list is an invalid account number.</p>
                  <p>The accepted currency for transactions at the moment, is the <b>Kenyan Shilling (KES)</b>. Only Kenyan phone numbers (with country code <i>+254 </i>) are allowed in the phone number input fields.</p>
					      </div>
					    </div>
				  </div>
				</div>
			</div>
		</div>
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h4>CO-OPBank API</h4>
        </div>
        <form target="_blank" action="{{ route('org-coopbank.request') }}" method="POST">
          <div class="card-body">
            @csrf
            <div class="form-group">
              <label for="action">Select Action</label>
              <select name="action" id="action" class="form-control" required>
                <option value="">Select Option</option>
                <option value="balance">Check Balance</option>
                <option value="transactions">Transactions</option>
                <option value="statement">Account Statement</option>
                <option value="mini_statement">Mini Statement</option>
                <option value="validation">Account Validation</option>
                <option value="exchange">Exchange Rate</option>
                <option value="iftransfer">Internal Fund Transfer</option>
                <option value="pesalink_transfer">PesaLink Fund Transfer</option>
                <option value="pesalink_phone">PesaLink Send to Phone</option>
                <option value="mpesa">Send to M-Pesa</option>
                <option value="status">Transaction Status</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="message_reference">Message Reference</label>
              <input type="text" name="message_reference" value="40ca18c6765086089a14" class="form-control" readonly>
            </div>
            
            <div class="form-group extra account">
              <label for="account_number"><span class="extra iftransfer pesalink_transfer pesalink_phone mpesa">Source</span> Account Number</label>
              <input name="account_number" id="account_number" class="form-control" list="account_numbers" placeholder="Select an account number" required>
              <datalist id="account_numbers">
                <option value="36001873000">36001873000</option>
                <option value="36001873020">36001873020</option>
                <option value="36001873021">36001873021</option>
                <option value="36001873005">36001873005</option>
              </datalist>
            </div>
            
            <div class="form-group extra transactions">
              <label for="no_of_transactions">Count of Transactions</label>
              <input type="text" name="no_of_transactions" id="no_of_transactions" value="1" placeholder="Number of Transactions" class="form-control" required>
            </div>
            
            <div class="form-group extra statement">
              <label for="period">Period</label>
              <input type="text" name="period" id="period" placeholder="Select Period" class="form-control daterange" required>
            </div>
            
            <div class="form-row row extra exchange">
              <div class="form-group col-sm-6">
                <label for="currency_from">From</label>
                <select class="form-control" name="currency_from" id="currency_from" required>
                  <option value="">Select a Currency</option>
                  <option>GBP</option>
                  <option>KES</option>
                  <option>USD</option>
                </select>
              </div>
              <div class="form-group col-sm-6">
                <label for="currency_to">To</label>
                <select class="form-control" name="currency_to" id="currency_to" required>
                  <option value="">Select a Currency</option>
                  <option>GBP</option>
                  <option>KES</option>
                  <option>USD</option>
                </select>
              </div>
            </div>
            
            <div class="form-group extra iftransfer pesalink_transfer">
              <label for="destination_account">Destination Account Number</label>
              <input type="text" name="destination_account" id="destination_account" placeholder="Destination Account Number" class="form-control" required>
            </div>
            
            <div class="form-group extra pesalink_transfer">
              <label for="bank_code">Destination Bank Code</label>
              <input type="text" name="bank_code" value="11" id="bank_code" placeholder="Enter Bank Code" class="form-control" required>
            </div>
            
            <div class="form-group extra pesalink_phone mpesa">
              <label for="phone_number">Phone Number</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">+254</span>
                </div>
                <input type="text" name="phone_number" id="phone_number" placeholder="Type phone number here" class="form-control" required>
              </div>
            </div>
            
            <div class="form-row row extra iftransfer pesalink_transfer pesalink_phone mpesa">
              <div class="form-group col-sm-3">
                <label for="currency">Currency</label>
                <select class="form-control" name="currency" id="currency" required>
                  <option value="">Currency</option>
                  <option>KES</option>
                </select>
              </div>
              <div class="form-group col-sm-9">
                <label for="amount">Amount</label>
                <input type="text" class="form-control" name="amount" id="amount" required>
              </div>
            </div>
            
            <div class="form-group extra iftransfer pesalink_transfer pesalink_phone mpesa">
              <label for="narration">Narration</label>
              <input type="text" name="narration" id="narration" placeholder="Max 20 chars" class="form-control" required>
            </div>
            
            <div class="form-group extra iftransfer pesalink_transfer pesalink_phone mpesa status">
              <label for="reference_number">Reference Number</label>
              <input type="text" name="reference_number" value="40ca18c6765086089a1" id="reference_number" placeholder="Enter Transaction Reference Number" class="form-control" required>
            </div>
            
            {{-- <div class="form-group extra status">
              <label for="reference_number">Reference Number</label>
              <input type="text" name="reference_number" value="40ca18c6765086089a1" id="reference_number" placeholder="Enter Transaction Reference Number" class="form-control" required>
            </div> --}}
          </div>
          
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Send Request</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('spec-scripts')
    <script src="{{ asset('assets/bundles/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <script>
      $(function () {
        var showHideFields = function(selectors, show=true, except=""){
          if(show){
            $(selectors).not(except).slideDown().find('.form-control').attr('required', '');
            // $(selectors).not(except).find('.form-control').attr('required', '')
          }
          else{
            $(selectors).not(except).slideUp().find('.form-control').removeAttr('required');
            // $(selectors).not(except).find('.form-control').removeAttr('required')
          }
        }

        showHideFields(".extra", false);

        var noAccount = ["status", "exchange"];

        $("#action").change(function(){
          let action = $(this).val(), except = "";
          if(!noAccount.includes(action)){
            except = ".account";
            showHideFields('.account');
          }
          showHideFields(".extra", false, except);
          if(action != ""){
            showHideFields(`.${action}`)
          }
          else{
            showHideFields('.extra', false);
          }
        });

        if($(".daterange").length > 0){
          $(".daterange").daterangepicker({
            endDate: moment().startOf('day'),
            startDate: moment().startOf('day').subtract(3, 'month'),
            locale: {
              format: "YYYY-MM-DD"
            }
          });
        }
      });
    </script>
@endsection