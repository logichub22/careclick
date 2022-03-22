@component('mail::message')
# Dear {{ $data['name'] }}

You are now {{ $data['admin'] !== null ? 'the administrator' : 'a member' }} of {{ $data['organization'] }}. Here are your log in credentials. 

<p>
	<strong>Email:</strong> {{ $data['email'] }} <br>
	<strong>Password:</strong> {{ $data['password'] }}
</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
