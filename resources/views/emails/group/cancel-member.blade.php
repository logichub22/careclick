@component('mail::message')
# Hi {{ $data['name'] }}

<p>
	Your membership to {{ $data['group'] }} savings group has been cancelled. While you are still a member of this group, this means that you can no longer access some group services.
</p>

<p>Contact the group administrator for more information on why this could be the case.</p>

Thanks,<br>
Group Admin.
@endcomponent
