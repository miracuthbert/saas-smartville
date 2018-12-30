<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <span class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                @include('components._pages_dropdown')

                @auth
                @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                @guest
                    {{-- Authentication Links --}}
                    <li class="nav-item">
                        <a class="nav-link{{ return_if(on_page('login'), ' active') }}" href="{{ route('login') }}">
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link{{ return_if(on_page('register'), ' active') }}"
                           href="{{ route('register') }}">
                            Sign Up
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown"><!-- My Companies -->
                        <a id="navbarCompanyDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            My Companies <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarCompanyDropdown">
                            @forelse($user_companies as $company)
                                <a class="dropdown-item" href="{{ route('tenant.switch', $company) }}">
                                    {{ $company->name }}
                                </a>
                            @empty
                                <div class="dropdown-item disabled">No companies found.</div>
                            @endforelse

                            <div class="dropdown-divider"></div>
                            <!-- Create New Company Link -->
                            <a class="dropdown-item" href="{{ route('account.companies.create') }}">
                                New company
                            </a>

                            <!-- View All Link -->
                            <a class="dropdown-item" href="{{ route('account.companies.index') }}">
                                View all
                            </a>
                        </div>
                    </li>

                    <!-- Notifications -->
                    <li class="nav-item">
                        <a class="nav-link{{ return_if(on_page('account.dashboard.notifications.index'), ' active') }}"
                           href="{{ route('account.dashboard.notifications.index') }}" title="Notifications">
                            <span><notification-badge/></span> <i class="icon-bell"></i> <span class="d-inline d-md-none">Notifications</span>
                        </a>
                    </li>

                    <!-- My Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link{{ return_if(on_page('account.dashboard'), ' active') }}"
                           href="{{ route('account.dashboard') }}" title="My Dashboard">
                            <i class="icon-speedometer"></i> <span class="d-inline d-md-none">My Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarUserDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                           title="{{ Auth::user()->name }}">
                            <i class="icon-user"></i>
                            <span class="d-inline d-md-none">{{ Auth::user()->name }}</span>
                            <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarUserDropdown">
                            <!-- User -->
                            <div class="dropdown-item">{{ Auth::user()->name }}</div>
                            <div class="dropdown-divider"></div>

                            <!-- Impersonating -->
                            @impersonating
                            <a class="dropdown-item" href="#"
                               onclick="event.preventDefault(); document.getElementById('impersonate-form').submit();">
                                Stop Impersonating
                            </a>
                            <form id="impersonate-form" action="{{ route('admin.users.impersonate.destroy') }}"
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endimpersonating

                            <!-- Admin Panel Link -->
                            @role('admin')
                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                Admin Panel
                            </a>
                            @endrole

                            <!-- User Account Link -->
                            <a class="dropdown-item" href="{{ route('account.index') }}">
                                My Account
                            </a>

                            <!-- Developer Link -->
                            {{--<a class="dropdown-item" href="{{ route('developer.index') }}">--}}
                            {{--Developer Panel--}}
                            {{--</a>--}}
                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            @include('layouts.partials.forms._logout')
                        </div>
                    </li>
                @endguest
            </ul>
    </div>
</nav>
