@component('mail::message')
# Hello

You have a new message from {{ $data['name'] }}.

<p>The message is as follows:</p>

<p>{{ $data['message'] }}</p>

<p>
	You can contact {{ $data['name'] }} via {{ $data['email'] }} or {{ $data['telephone'] }}.
	Contact Details <br>
	<strong>Name:</strong> {{ $data['name'] }} <br>
	<strong>Company:</strong> {{ $data['company'] }} <br>
	<strong>Email:</strong> {{ $data['email'] }} <br>
	<strong>Telephone:</strong> {{ $data['telephone'] }} 
</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
