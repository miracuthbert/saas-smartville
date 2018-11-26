@extends('admin.layouts.default')

@section('admin.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('admin.users.index') }}">Users</a>
    </li>
    <li class='breadcrumb-item'>Roles</li>
@endsection

@section('admin.content')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <strong>Roles</strong>

                <a class="pull-right" href="{{ route('admin.roles.create') }}">Add new role</a>
            </div>
        </div>
    </div>

    @if($roles->total())
        <div class="table-responsive mb-3">
            <table class="table table-hover table-outline mb-0">
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
                @include('admin.users.roles.partials._role', compact('roles'))
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
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
