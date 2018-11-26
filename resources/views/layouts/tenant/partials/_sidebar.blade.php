<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link{{ return_if(on_page('tenant.dashboard'), ' active') }}"
                   href="{{ route('tenant.dashboard') }}">
                    <i class="icon-speedometer"></i> Dashboard
                </a>
            </li>
            @yield('sidebar')
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
