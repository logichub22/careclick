@component('mail::message')
# Dear {{ $data['name'] }}

You are now a group admin and member of {{ $data['group'] }}. . 

<p>
	This means you can add fellow members to {{ $data['group'] }} as well as manage them.
</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
