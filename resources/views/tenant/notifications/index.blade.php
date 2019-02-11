@extends('tenant.layouts.default')

@section('title', page_title('Company Notifications'))

@section('tenant.breadcrumb')
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Notifications</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <notifications endpoint="{{ route('tenant.notifications.index') }}"/>
        </div>
    </div>
@endsection
