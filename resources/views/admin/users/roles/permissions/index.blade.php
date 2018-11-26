@extends('admin.layouts.default')

@section('admin.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('admin.users.index') }}">Users</a>
    </li>
    <li class='breadcrumb-item'>
        <a href="{{ route('admin.roles.index') }}">Roles</a>
    </li>
    <li class='breadcrumb-item'>
        {{ $role->name }}
    </li>
    <li class='breadcrumb-item active'>Permissions</li>
@endsection

@section('admin.content')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <strong>{{ $role->name }} Permissions</strong>
            </div>

            <form action="{{ route('admin.roles.permissions.store', $role) }}" method="post">
                {{ csrf_field() }}

                @include('admin.users.roles.partials.forms._permissions', compact('role'))

                <div class="form-group row">
                    <div class="col-sm-4 offset-sm-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection