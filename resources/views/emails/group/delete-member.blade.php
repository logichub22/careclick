@component('mail::message')
# Hi {{ $data['name'] }}

<p>
	We are sorry to inform you that you are no longer a member of {{ $data['group'] }}.
</p>

Thanks,<br>
Group Admin.
@endcomponent
