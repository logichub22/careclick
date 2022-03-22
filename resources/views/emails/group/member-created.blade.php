@component('mail::message')
# Dear {{ $data['name'] }}

You are now a {{ $data['trainer'] == null ? 'trainer under ' : 'member of ' }} {{ $data['group'] }}. Here are your log in credentials. 

<p>
	<strong>Email:</strong> {{ $data['email'] }} <br>
	<strong>Password:</strong> {{ $data['password'] }} <br>
</p>

{{-- <p>
	Please note that you can accept or turn down this invitation.
</p> --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
