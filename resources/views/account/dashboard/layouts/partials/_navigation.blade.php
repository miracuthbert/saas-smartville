<div class="d-block d-sm-none mt-3 mb-4">
    <button class="btn btn-light btn-block flex-menu-toggler" data-target="#accountDashboardMenu">
        Go to... <span class="icon-arrow-down"></span>
    </button>
</div>

<div class="d-none d-sm-block mb-4" id="accountDashboardMenu">
    <ul class="nav flex-column nav-pills">
        <li class="nav-item">
            <a class="nav-link{{ return_if(on_page('account.dashboard'), ' active') }}"
               href="{{ route('account.dashboard') }}">
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ return_if(on_page('account.leases.index'), ' active') }}"
               href="{{ route('account.leases.index') }}">
                Leases
            </a>
        </li>
        <div class="nav-item h6 px-3 mt-3">Invoices</div>
        <li class="nav-item">
            <a class="nav-link{{ return_if(on_page('account.invoices.rent.index'), ' active') }}"
               href="{{ route('account.invoices.rent.index') }}">
                Rent Invoices
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ return_if(on_page('account.invoices.utilities.index'), ' active') }}"
               href="{{ route('account.invoices.utilities.index') }}">
                Utility Invoices
            </a>
        </li>
        <div class="nav-item h6 px-3 mt-3">Payment History</div>
        <li class="nav-item">
            <a class="nav-link{{ return_if(on_page('account.payments.rent.index'), ' active') }}"
               href="{{ route('account.payments.rent.index') }}">
                Rent Payments
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ return_if(on_page('account.payments.utilities.index'), ' active') }}"
               href="{{ route('account.payments.utilities.index') }}">
                Utilities Payments
            </a>
        </li>
    </ul>
</div>
