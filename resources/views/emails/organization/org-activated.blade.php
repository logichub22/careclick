@component('mail::message')
# Dear {{ $data['name'] }}

Your organization by the name {{ $data['organization'] }} has been activated. 

<p>
	This means that you can now log in, create loan packages and access all other services that are linked to your organization.
</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
