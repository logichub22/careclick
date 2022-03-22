@component('mail::message')
# Dear {{ $data['name'] }}

You have successfully changed your password.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
