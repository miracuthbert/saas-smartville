@extends('tenant.layouts.default')

@section('title', page_title('Roles'))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item active'>Roles</li>
@endsection

@section('tenant.content')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <strong>Roles</strong>

                @can('create company roles')
                    <a class="pull-right" href="{{ route('tenant.roles.create') }}">Add new role</a>
                @endcan
            </div>
        </div>
    </div>

    @if($roles->total())
        <table class="table table-responsive-sm table-hover table-outline mb-0">
            <thead class="thead-light">
            <tr>
                <th>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="selectAll">
                        <label class="custom-control-label" for="selectAll">&nbsp;</label>
                    </div>
                </th>
                <th>Name</th>
                <th>Usable</th>
                <th>Permissions</th>
                <th>Users</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @include('tenant.roles.partials._role', compact('roles'))
            </tbody>
        </table>

        <!-- Pagination -->
        <nav role="navigation">
            {{ $roles->links() }}
        </nav>

        <!-- Confirm Modal -->
        @includeIf('layouts.partials.modals._js_confirm_modal')
    @else
        <div class="card card-body">
            <div class="card-text">No roles found.</div>
        </div>
    @endif
@endsection

@push('scripts')
    @includeIf('layouts.partials.modals._script_for_confirm_modal')
@endpush