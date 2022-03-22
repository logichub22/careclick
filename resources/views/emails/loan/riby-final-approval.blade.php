@component('mail::message')
# Dear {{ $data['admin_name'] }},

A request of NGN {{ number_format($data['amount']) }} by {{ $data['applicant_name'] }} on your loan package titled <b>{{ $data['package_name'] }}</b>, has passed first approval, and is now awaiting your final approval.

Please <a href="{{ route('organization.requests') }}">log on to your dashboard</a> to view, and approve or decline the request.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
