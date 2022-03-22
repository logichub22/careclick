@component('mail::message')
# Dear {{ $data['name'] }}

Your organization by the name {{ $data['organization'] }} has been deactivated. 

<p>
	This means that you can no longer log in, create loan packages and access all other services that are linked to your organization.
</p>

<p>
    Please contact <a href="mailto:support2@edgetech.co.ke">support</a> for more information.
</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
