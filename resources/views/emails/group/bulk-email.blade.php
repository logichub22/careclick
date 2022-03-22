@component('mail::message')
# Hi {{ $data['name'] }}

<p>
	{{ $data['message'] }}
</p>

Thanks,<br>
{{ $data['group'] }} Admin
@endcomponent
