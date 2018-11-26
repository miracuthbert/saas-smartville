<header class="app-header navbar">
    <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand no-brand-logo" href="{{ url('/') }}">{{ config('app.name') }}</a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>

    <ul class="nav navbar-nav d-md-down-none">
        <li class="nav-item px-3">
            <a class="nav-link" href="{{ route('tenant.dashboard') }}">Dashboard</a>
        </li>
    </ul>
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item px-3 d-md-down-none">
            <a class="nav-link" href="{{ url('/') }}">Home</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button"
               aria-haspopup="true" aria-expanded="false">
                <img src="{{ url('img/avatars/default_avatar.png') }}" class="img-avatar"
                     alt="{{ auth()->user()->name }}">
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('account.dashboard') }}">
                    <i class="icon-speedometer"></i> My Dashboard
                </a>
                <a class="dropdown-item" href="{{ route('account.index') }}">
                    <i class="fa fa-user"></i> Account
                </a>
                <div class="divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-lock"></i> Logout
                </a>
                @include('layouts.partials.forms._logout')
            </div>
        </li>
    </ul>
</header>
