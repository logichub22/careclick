@switch($attributes)
    @case("user.dashboard")
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb m-0 bg-dark text-light-all">
            <li class="breadcrumb-item active font-bold" aria-current="page">Dashboard</li>
        </ol>
    </nav>
    @break

    @case("user-groups.index")
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb m-0 bg-dark text-light-all">
            <li class="breadcrumb-item"><a href="{{ route( $user.'.overview') }}">Dashboard</a></li>
            <li class="breadcrumb-item active font-bold" aria-current="page">Groups</li>
        </ol>
    </nav>
    @break

    @endswitch
