@component('mail::message')
# Dear {{ $data['name'] }}

<p>
	You account has been deactivated. This means you can no longer access the system.
</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
