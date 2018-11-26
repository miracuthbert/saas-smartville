@extends('tenant.layouts.default')

@section('title', page_title('Account Overview'))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item active'>Account</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <h4>Account Overview</h4>
            </div>
        </div>
        <div class="list-group list-group-flush">
            <div class="list-group-item">
                <h4>Name</h4>
                <p>{{ request()->tenant()->name }}</p>
            </div>
            <div class="list-group-item">
                <h4>Email Address</h4>
                <p>{{ request()->tenant()->email }}</p>
            </div>
            <div class="list-group-item">
                <h4>Team members</h4>
                <p>{{ request()->tenant()->users->count() }}</p>
            </div>
        </div>
    </div>
@endsection