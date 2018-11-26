<!-- Breadcrumb -->
<ol class="breadcrumb">
    <li class="breadcrumb-item">{{ config('app.name') }}</li>
    @yield('breadcrumb')

    <!-- Breadcrumb Menu-->
    <li class="breadcrumb-menu d-md-down-none">
        <div class="btn-group" role="group" aria-label="Button group">
            <a class="btn" href="{{ route('tenant.dashboard') }}">
                <i class="icon-graph"></i> &nbsp;Dashboard
            </a>
            @yield('breadcrumb-menu')
        </div>
    </li>
</ol>
