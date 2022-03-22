@component('mail::message')
# Dear {{ $user->name }}

@if($user->hasRole('service-provider'))
You are now registered as a service provider in {{ config('app.name') }}.
@else
Thank you so much for registering with us.
@endif

<p>We may need to send you critical information regarding our services hence the need to verify your email.</p>

@component('mail::button', ['url' => config('app.url') . '/register/verify/' . $user->token, 'color' => 'blue'])
Verify Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
