@extends('tenant.layouts.default')

@section('title', page_title("Edit Member | {$user->name}"))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.account.team.index') }}">Team</a>
    </li>
    <li class='breadcrumb-item active'>Edit Member</li>
@endsection

@section('tenant.content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                Edit Team Member
            </h4>

            <form method="POST" action="{{ route('tenant.account.team.update', $user) }}">
                @csrf

                <div class="form-group row">
                    <label for="name" class="col-md-4 control-label">Name</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control-plaintext" name="name"
                               value="{{ $user->name }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-4 control-label">E-mail Address</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control-plaintext" name="email"
                               value="{{ $user->email }}" readonly>
                    </div>
                </div>

                <!-- todo: add datepicker to remove user membership rather than delete -->
                <!-- todo: add roles vue component to assign user roles & permissions -->

                <div class="form-group row d-none">
                    <div class="col-md-6 offset-md-4">
                        <button type="button" class="btn btn-primary" disabled>Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection