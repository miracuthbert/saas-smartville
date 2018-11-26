@extends('admin.layouts.default')

@section('admin.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('admin.users.index') }}">Users</a>
    </li>
    <li class='breadcrumb-item active'>Permissions</li>
@endsection

@section('admin.content')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <h4>Permissions</h4>

                    <aside>
                        <a href="{{ route('admin.permissions.create') }}">Add new permission</a>
                        @if(config('app.debug'))
                            <a href="{{ route('admin.permissions.seed') }}">Seed</a>
                        @endif
                    </aside>
                </div>
            </div>
        </div>
    </div>

    @if($permissions->total())
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
                    <th>For company <span class="icon-info" title="Tenant based permission"></span></th>
                    <th>Usable</th>
                    <th>Roles</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($permissions as $permission)
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="permission{{ $permission->id }}"
                                       value="{{ $permission->id }}">
                                <label class="custom-control-label" for="permission{{ $permission->id }}">&nbsp;</label>
                            </div>
                        </td>
                        <td>{{ $permission->name }}</td>
                        <td>
                            {{ $permission->for_company ? 'True' : 'False' }}
                        </td>
                        <td>
                            {{ $permission->usable ? 'True' : 'False' }}
                        </td>
                        <td>
                            {{ $permission->for_company ? $permission->companyRoles->count() : $permission->roles->count() }}
                        </td>
                        <td>
                            <nav class="nav" role="navigation" aria-label="Role options">
                                <a class="nav-item nav-link"
                                   href="{{ route('admin.permissions.edit', $permission) }}">
                                    Edit
                                </a>

                                <a class="nav-item nav-link"
                                   href="{{ route('admin.permissions.toggleStatus', $permission) }}"
                                   onclick="event.preventDefault(); document.getElementById('permission-toggle-status-form-{{ $permission->id }}').submit()">
                                    {{ $permission->usable ? 'Disable' : 'Activate' }}
                                </a>

                                <!-- Toggle Status Form -->
                                <form action="{{ route('admin.permissions.toggleStatus', $permission) }}"
                                      method="post"
                                      style="display: none;"
                                      id="permission-toggle-status-form-{{ $permission->id }}">
                                    {{ csrf_field() }}
                                    {{ method_field('PUT') }}
                                </form>

                                <!-- Delete Button -->
                                <a class="nav-item nav-link text-danger"
                                   href="{{ route('admin.permissions.destroy', $permission) }}" data-toggle="modal"
                                   data-target="#permission-delete-modal-{{ $permission->id }}">
                                    Delete
                                </a>

                                @include('layouts.admin.partials.modals._confirm_modal', [
                                    'modalId' => "permission-delete-modal-{$permission->id}",
                                    'title' => "Delete confirmation",
                                    'action' => "permission-delete-form-{$permission->id}",
                                    'message' => "Do you want to delete: {$permission->name} permission?",
                                    'warning' => "Permission will only be removed if it has no users.
                                    To prevent further access disable permission and revoke it from users.",
                                    'type' => "danger"
                                ])

                                <form action="{{ route('admin.permissions.destroy', $permission) }}"
                                      method="post"
                                      style="display: none;"
                                      id="permission-delete-form-{{ $permission->id }}">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>

                                @if(!$permission->for_company)
                                    <a class="nav-item nav-link"
                                       href="{{ route('admin.roles.index', ['permission' => $permission]) }}">
                                        Manage roles
                                    </a>
                                @endif
                            </nav>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <nav role="navigation">
            {{ $permissions->links() }}
        </nav>
    @else
        <div class="card card-body">
            <div class="card-text">No permissions found.</div>
        </div>
    @endif
@endsection