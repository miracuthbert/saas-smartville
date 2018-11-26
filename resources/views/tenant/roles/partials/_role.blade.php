@foreach($roles as $role)
    <tr>
        <td>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="utility{{ $role->id }}">
                <label class="custom-control-label" for="utility{{ $role->id }}">&nbsp;</label>
            </div>
        </td>
        <td>{{ $role->name }}</td>
        <td>
            {{ $role->usable ? 'True' : 'False' }}
        </td>
        <td>
            {{ $role->permissions->count() }}
        </td>
        <td>
            {{ $role->users->count() }}
        </td>
        <td>
            <ul class="nav" role="navigation" aria-label="Role options">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tenant.roles.edit', $role) }}">
                        Edit
                    </a>
                </li>

                @if(!($role->users->count()))
                    <li class="nav-item">
                        <!-- Delete Button -->
                        <a class="nav-link text-danger"
                           href="{{ route('tenant.roles.destroy', $role) }}"
                           data-toggle="modal" data-target="#confirmModal"
                           data-title="Role Delete confirmation"
                           data-message="Do you want to delete: &bprime;<strong>{{ $role->name }}</strong>&prime; role?"
                           data-warning="Role will only be removed if it has no users.
                            To prevent further access disable role and revoke it from users."
                           data-type="danger"
                           data-action="role-delete-form-{{ $role->id }}">
                            Delete
                        </a>

                        <form action="{{ route('tenant.roles.destroy', $role) }}"
                              method="post"
                              style="display: none;"
                              id="role-delete-form-{{ $role->id }}">
                            @csrf
                            @method('DELETE')
                        </form>
                    </li><!-- /.nav-item -->
                @endif

                <li class="nav-item dropdown">
                    <!-- Users options -->
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        Users
                    </a>
                    <div class="dropdown-menu">
                        @if($role->users->count())
                            <a class="dropdown-item" href="{{ route('tenant.roles.users.destroy', $role) }}"
                               data-toggle="modal" data-target="#confirmModal"
                               data-title="Revoke role from all users confirmation"
                               data-message="Do you want to revoke access of users with &bprime;{{ $role->name }}&prime; role?"
                               data-warning="Once completed, this action cannot be undone."
                               data-type="danger"
                               data-action="revoke-role-users-access-form-{{ $role->id }}">
                                Revoke from all users
                            </a>
                        @endif

                        <a class="dropdown-item"
                           href="{{ route('tenant.roles.users.index', $role) }}">
                            Manage users
                        </a>
                    </div><!-- /.dropdown-menu -->

                    <!-- Revoke User Access Form -->
                    <form action="{{ route('tenant.roles.users.destroy', $role) }}"
                          method="post"
                          style="display: none;"
                          id="revoke-role-users-access-form-{{ $role->id }}">
                        @csrf
                        @method('DELETE')
                    </form>
                </li><!-- /.nav-item -->
            </ul>
        </td>
    </tr>
@endforeach
