@component('mail::message')
# Dear User,

A new loan request of {{ $data['currency'] . ' ' . number_format($data['principal']) }} has been made on your loan package titled {{ $data['package'] }} Here is the summary: 

@component('mail::table')
| Description   | Table         |
| ------------- |:-------------:|
| Amount Borrowed     | {{ $data['currency'] . ' ' . number_format($data['principal']) }} |
| Annual Interest Rate     | {{ $data['interest'] }}% |
| Interest Charge Frequency      | {{ $data['interest_charge_frequency'] }} |
| Number of Installments      | {{ $data['installments'] }} |
{{-- | Last Payment Date     | {{ $data['date'] }} | --}}
| Total Amount to Pay | {{ $data['currency'] . ' ' . number_format($data['total']) }} |
| Your Earnings | {{ $data['currency'] . ' ' . number_format($data['interest_due']) }} |
@endcomponent

<p>Kindly <a href="{{ route('organization.requests') }}">visit the loan requests page on your dashboard</a> to view and approve this loan request. Please note that a sum of {{ $data['currency'] . ' ' . number_format($data['principal']) }} will be deducted from your account upon approval</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
