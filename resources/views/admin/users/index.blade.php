@extends('admin.layouts.default')

@section('admin.breadcrumb')
    <li class='breadcrumb-item active'>Users</li>
@endsection

@section('admin.content')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <h4>Users</h4>
                    <a class="pull-right" href="{{ route('admin.users.create') }}">Add new user</a>
                </div>
            </div>

            <div class="my-1">
                <p class="h6">Filters</p>

                <div class="row">
                    <div class="col-sm-12">
                        @include('admin.users.partials._filters')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($users->total())
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
                    <th>Email</th>
                    <th>Verified</th>
                    <th>Last seen</th>
                    <th>Joined</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="user{{ $user->id }}">
                                <label class="custom-control-label" for="user{{ $user->id }}">&nbsp;</label>
                            </div>
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>
                            {{ $user->email }}
                        </td>
                        <td>{{ $user->activated ? 'True' : 'False' }}</td>
                        <td>{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : '' }}</td>
                        <td>{{ $user->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('admin.users.roles.index', $user) }}">Manage roles</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <nav role="navigation">
            {{ $users->links() }}
        </nav>
    @else
        <div class="card card-body">
            <div class="card-text">No users found.</div>
        </div>
    @endif
@endsection