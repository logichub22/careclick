@component('mail::message')
# Dear {{ $data['name'] }}

<p>
	You account has been activated. 
</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
