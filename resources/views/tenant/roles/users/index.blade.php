@extends('tenant.layouts.default')

@section('title', page_title("{$companyRole->name}"))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.roles.index') }}">Roles</a>
    </li>
    <li class='breadcrumb-item'>{{ $companyRole->name }}</li>
    <li class='breadcrumb-item active'>Users</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <section>
                        <h4>{{ $companyRole->name }} Users</h4>

                        <div class="media">
                            <div class="media-body">
                                <h6>Permissions</h6>
                                <ul class="list-inline">
                                    @foreach($companyRole->permissions as $permission)
                                        <li class="list-inline-item mb-1">
                                            <div class="badge badge-primary badge-pill">{{ $permission->name }}</div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div><!-- /.media -->
                    </section>

                    <aside>
                        <div class="dropdown dropleft">
                            <button class="btn btn-link dropdown-toggle" type="button" id="roleMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="roleMenuButton">
                                <h6 class="dropdown-header">Role options</h6>

                                <a href="#collapseAddMember" class="dropdown-item" data-toggle="collapse" role="button"
                                   aria-expanded="false" aria-controls="collapseAddMember">
                                    Assign to users
                                </a>

                                @if($companyRole->users->count())
                                    <a class="dropdown-item"
                                       href="{{ route('tenant.roles.users.destroy', $companyRole) }}"
                                       data-toggle="modal" data-target="#confirmModal"
                                       data-title="Revoke role from all users confirmation"
                                       data-message="Do you want to revoke access of users with &bprime;{{ $companyRole->name }}&prime; role?"
                                       data-warning="Once completed, this action cannot be undone."
                                       data-type="danger"
                                       data-action="revoke-role-users-access-form-{{ $companyRole->id }}">
                                        Revoke from all users
                                    </a>
                                @endif
                            </div>
                        </div><!-- /.dropdown -->

                        {{-- Revoke Role Users Access Form --}}
                        <form action="{{ route('tenant.roles.users.destroy', $companyRole) }}"
                              method="POST"
                              id="revoke-role-users-access-form-{{ $companyRole->id }}" style="display: none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </aside>
                </div><!-- /.d-flex -->
            </div><!-- /.card-title -->
            <hr>

            <!-- Assign Role Form -->
            <div class="collapse mb-3" id="collapseAddMember">
                <h6>Assign Role to Team Members</h6>

                <form method="POST" action="{{ route('tenant.roles.users.store', $companyRole) }}">
                    @csrf

                    @datetimepicker([
                    'field_name' => 'expires_at',
                    'label' => 'Expires at',
                    'description' => 'Date user role expires.'
                    ])
                    @enddatetimepicker

                    <div class="form-group row">
                        <label class="col-md-4 control-label">Team Members</label>
                        <div class="col-md-8">
                            <div class="form-text">
                                Select team members below to assign <strong>{{ $companyRole->name }}</strong> role.
                            </div>

                            @if($members->count())
                                <div class="table-responsive-sm mb-3">
                                    <table class="table table-borderless">
                                        <thead>
                                        <tr>
                                            <th>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="selectAll">
                                                    <label class="custom-control-label" for="selectAll">&nbsp;</label>
                                                </div>
                                            </th>
                                            <th>Name</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($members as $member)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="users[{{ $member->id }}][id]"
                                                               class="custom-control-input" id="users{{ $member->id }}"
                                                               value="{{ $member->id }}"
                                                                {{ array_has(old('users'), $member->id) ? ' checked' : '' }}>
                                                        <label class="custom-control-label"
                                                               for="users{{ $member->id }}">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>{{ $member->name }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="form-text">Sorry, no team members found.</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-link" data-target="#collapseAddMember"
                                    data-toggle="collapse" aria-expanded="false" aria-controls="collapseAddMember">
                                Hide form
                            </button>
                        </div>
                    </div>
                </form>
            </div><!-- /.collapse -->

            @if($users->count())
                <div class="accordion" id="usersAccordion">
                    <div class="table-responsive-sm mb-3">
                        <table class="table table-hover table-borderless">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Expired At</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>
                                        @if($user->pivot->expires_at)
                                            <div>
                                                {{ dateParse($user->pivot->expires_at)->toDayDateTimeString() }}
                                            </div>
                                        @else
                                            <div id="user{{ $user->id }}" class="collapse"
                                                 aria-labelledby="user{{ $user->id }}" data-parent="#usersAccordion">
                                                <form action="{{ route('tenant.roles.users.update', [$companyRole, $user]) }}"
                                                      method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    @datetimepicker([
                                                    'field_name' => "users[{$user->id}][expires_at]",
                                                    'label' => 'Expires at',
                                                    'description' => 'Date user role expires.',
                                                    'horizontal' => false
                                                    ])
                                                    @enddatetimepicker

                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div>
                                                <a class="toggleExpiryForm" href="#" role="button"
                                                   data-toggle="collapse" data-target="#user{{ $user->id }}"
                                                   aria-expanded="false" aria-controls="user{{ $user->id }}">
                                                    Set {{ $user->first_name }}'s role expiry date
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-link" role="button"
                                               href="{{ route('tenant.roles.users.update', [$companyRole, $user]) }}"
                                               data-toggle="modal" data-target="#confirmModal"
                                               data-title="Revoke role from user confirmation"
                                               data-message="Do you want to revoke &bprime;{{ $companyRole->name }}&prime; role from {{ $user->name }}?"
                                               data-warning="Once completed, this action cannot be undone."
                                               data-type="danger"
                                               data-action="revoke-role-user-access-form-{{ $user->id }}">
                                                <i class="fa fa-trash"></i> <span class="sr-only">Revoke</span>
                                            </a>
                                        </div>

                                        {{-- Revoke Role User Form --}}
                                        <form action="{{ route('tenant.roles.users.update', [$companyRole, $user]) }}"
                                              method="POST"
                                              id="revoke-role-user-access-form-{{ $user->id }}" style="display: none">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table><!-- /.table -->
                    </div><!-- /.table-responsive-sm -->
                </div>
            @else
                <p>No users found.</p>
            @endif
        </div><!-- /.card-body -->
    </div><!-- /.card -->

    <!-- Confirm Modal -->
    @includeIf('layouts.partials.modals._js_confirm_modal')
@endsection

@push('scripts')
    @includeIf('layouts.partials.modals._script_for_confirm_modal')
    <script>
        var text = null

        $('#usersAccordion .collapse').on('shown.bs.collapse', function () {
            var button = $(this).next().find('.toggleExpiryForm')
            text = button.text()
            button.text('Hide')
        }).on('hidden.bs.collapse', function () {
            var button = $(this).next().find('.toggleExpiryForm')
            button.text(text)
        })
    </script>
@endpush