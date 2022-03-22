<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('federation.dashboard') }}"> <img alt="image" src="{{ asset('assets/img/logo.png') }}"
                                                    class="header-logo"/>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown">
                <a href="{{ route('federation.dashboard') }}" class="waves-effect  waves-light">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="s-text">Dashboard</span>
                </a>
            </li>
            <li class="menu-header">Organization Information</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-users"></i><span>Associations</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('associations.index') }}">My Associations</a></li>
                    <li><a class="nav-link" href="{{ route('associations.create') }}">Add Association</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i class="far fa-credit-card"></i><span>Trainers</span></a>
                <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('trainers.index') }}">Trainers</a></li>
                    <li><a class="nav-link" href="{{ route('trainers.create') }}">Add Trainers</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ route('federation.profile') }}" class="nav-link">
                    <i class="far fa-user"></i><span>Profile</span>
                </a>
            </li>
            </ul>
        </ul>
    </aside>
</div>
