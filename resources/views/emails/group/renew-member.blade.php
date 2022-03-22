@component('mail::message')
# Hi {{ $data['name'] }}

<p>
	Your membership to {{ $data['group'] }} savings group has been renewed. You can now access all services associated with this group.
</p>

Thanks,<br>
Group Admin.
@endcomponent
