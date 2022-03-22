@component('mail::message')
# Dear {{ $data['borrower_name']}},

Your loan request of {{ $data['currency'] }} {{ number_format($data['principal']) }} has been successfully approved. Here is the loan summary: 

@component('mail::table')
| Description   | Table         |
| ------------- |:-------------:|
| Loan Name     | {{ $data['loanName'] }}     |
| Principal     | &#8358;{{ number_format($data['principal']) }} |
| Annual Interest Rate     | {{ $data['interest'] }}% |
| Interest Charge Frequency      | {{ $data['interest_charge_frequency'] }} |
| Number of Installments      | {{ $data['installments'] }} |
| Pay By     | {{ $data['date'] }} |
| Total Amount to Pay | &#8358;{{ number_format($data['total']) }} |
@endcomponent

<p>{{ number_format($data['principal']) }} has been deposited into your account</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
