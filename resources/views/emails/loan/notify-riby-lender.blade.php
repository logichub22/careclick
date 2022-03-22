@component('mail::message')
# Dear {{ $data['admin_name'] }},

A new loan request of NGN {{ number_format($data['amount']) }} has been made on your loan package titled <b>{{ $data['package_name'] }}</b>

Please <a href="{{ route('organization.requests') }}">visit your dashboard</a> to view, and approve or decline the request.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
