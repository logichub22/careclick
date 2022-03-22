@extends('back/individual/layouts/master')

@section('title')
	Africastalking SMS API
@endsection

@section('one-step')
    / API
@endsection

@section('content')
	<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4>Testing Africastalking SMS API</h4>
        </div>
        <form action="{{ route('africastalking.send') }}" method="POST">
          <div class="card-body">
            @csrf
            
            <div class="form-group">
              <label for="sender">Sender ID</label>
              <input type="text" name="sender" value="Jamborow" class="form-control" readonly>
            </div>
            
            <div class="form-group">
              <label for="transaction">Transaction Type</label>
              <select name="transaction" id="transaction" class="form-control" placeholder="Enter phone number..." required>
                <option value="">Select transaction type</option>
                <option value="Credit">Credit</option>
                <option value="Debit">Debit</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="phone_number">Phone Number</label>
              <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="Enter phone number..." required>
            </div>
            
            <div class="form-group">
              <label for="message">Message</label>
              <textarea name="message" id="message" class="form-control" placeholder="" required></textarea>
            </div>
          </div>
          
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Send Request</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection