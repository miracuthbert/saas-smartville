@section('sidebar')
    <!-- Issues -->
    <li class="nav-item">
        <a class="nav-link{{ return_if(on_page('tenant.dashboard.issues.index'), ' active') }}"
           href="{{ route('tenant.dashboard.issues.index') }}">
            <i class="icon-exclamation"></i> Issues
        </a>
    </li>

    <!-- Amenities -->
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#">
            <i class="icon-layers"></i> Amenities
        </a>
        <ul class="nav-dropdown-items">
            <li class="nav-item">
                <a class="nav-link{{ return_if(on_page('tenant.amenities.create'), ' active') }}"
                   href="{{ route('tenant.amenities.create') }}">
                    <i class="icon-plus"></i> Add Amenity
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ return_if(on_page('tenant.amenities.index'), ' active') }}"
                   href="{{ route('tenant.amenities.index') }}">
                    <i class="icon-layers"></i> Amenities
                </a>
            </li>
        </ul>
    </li>
    <!-- Properties -->
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#">
            <i class="icon-home"></i> Properties
        </a>
        <ul class="nav-dropdown-items">
            <li class="nav-item">
                <a class="nav-link{{ return_if(on_page('tenant.properties.create'), ' active') }}"
                   href="{{ route('tenant.properties.create.start') }}">
                    <i class="icon-plus"></i> Add Property
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ return_if(on_page('tenant.properties.index'), ' active') }}"
                   href="{{ route('tenant.properties.index') }}">
                    <i class="icon-home"></i> Properties
                </a>
            </li>
        </ul>
    </li>
    <!-- Tenants -->
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#">
            <i class="icon-people"></i> Tenants
        </a>
        <ul class="nav-dropdown-items">
            <li class="nav-item">
                <a class="nav-link{{ return_if(on_page('tenant.tenants.create'), ' active') }}"
                   href="{{ route('tenant.tenants.create') }}">
                    <i class="icon-plus"></i> Add Tenant
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ return_if(on_page('tenant.tenants.index'), ' active') }}"
                   href="{{ route('tenant.tenants.index') }}">
                    <i class="icon-people"></i> Tenants
                </a>
            </li>
        </ul>
    </li>
    <!-- Rent Invoices -->
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#">
            <i class="icon-credit-card"></i> Rent Invoices
        </a>
        <ul class="nav-dropdown-items">
            <li class="nav-item">
                <a class="nav-link{{ return_if(on_page('tenant.rent.create'), ' active') }}"
                   href="{{ route('tenant.rent.invoices.create') }}">
                    <i class="icon-plus"></i> Add Invoice
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ return_if(on_page('tenant.rent.index'), ' active') }}"
                   href="{{ route('tenant.rent.invoices.index') }}">
                    <i class="icon-credit-card"></i> Rent Invoices
                </a>
            </li>
        </ul>
    </li>
    <!-- Utilities -->
    <li class="nav-title">Utilities / Services</li>
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#">
            <i class="icon-layers"></i> Utilities
        </a>
        <ul class="nav-dropdown-items">
            <li class="nav-item">
                <a class="nav-link{{ return_if(on_page('tenant.utilities.create'), ' active') }}"
                   href="{{ route('tenant.utilities.create') }}">
                    <i class="icon-plus"></i> Add Utility
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ return_if(on_page('tenant.utilities.index'), ' active') }}"
                   href="{{ route('tenant.utilities.index') }}">
                    <i class="icon-layers"></i> Utilities
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#">
            <i class="icon-credit-card"></i> Utility Invoices
        </a>
        <ul class="nav-dropdown-items">
            <li class="nav-item">
                <a class="nav-link{{ return_if(on_page('tenant.utilities.invoices.create'), ' active') }}"
                   href="{{ route('tenant.utilities.invoices.create') }}">
                    <i class="icon-plus"></i> Add Invoice
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ return_if(on_page('tenant.utilities.invoices.index'), ' active') }}"
                   href="{{ route('tenant.utilities.invoices.index') }}">
                    <i class="icon-credit-card"></i> Utility Invoices
                </a>
            </li>
        </ul>
    </li>
    <!-- Account -->
    <li class="nav-title">MANAGE ACCOUNT</li>
    <li class="nav-item">
        <a class="nav-link{{ return_if(on_page('tenant.account.index'), ' active') }}"
           href="{{ route('tenant.account.index') }}">
            <i class="icon-flag"></i> Account Overview
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ return_if(on_page('tenant.account.profile.index'), ' active') }}"
           href="{{ route('tenant.account.profile.index') }}">
            <i class="icon-organization"></i> Company Profile
        </a>
    </li>
    <!-- Team -->
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#">
            <i class="fa fa-users"></i> Team
        </a>
        <ul class="nav-dropdown-items">
            <li class="nav-item">
                <a class="nav-link{{ return_if(on_page('tenant.account.team.create'), ' active') }}"
                   href="{{ route('tenant.account.team.create') }}">
                    <i class="icon-plus"></i> Add Member
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ return_if(on_page('tenant.account.team.index'), ' active') }}"
                   href="{{ route('tenant.account.team.index') }}">
                    <i class="fa fa-users"></i> Members
                </a>
            </li>
        </ul>
    </li>

    <!-- Roles -->
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#">
            <i class="icon-lock"></i> Roles
        </a>
        <ul class="nav-dropdown-items">
            <li class="nav-item">
                <a class="nav-link{{ return_if(on_page('tenant.roles.create'), ' active') }}"
                   href="{{ route('tenant.roles.create') }}">
                    <i class="icon-plus"></i> Add Role
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link{{ return_if(on_page('tenant.roles.index'), ' active') }}"
                   href="{{ route('tenant.roles.index') }}">
                    <i class="icon-lock"></i> Roles
                </a>
            </li>
        </ul>
    </li>
@endsection