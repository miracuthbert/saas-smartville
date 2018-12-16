@extends('tenant.layouts.default')

@section('title', page_title('Company Issues'))

@section('tenant.breadcrumb')
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Issues</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <issues endpoint="{{ route('tenant.issues.index') }}"/>
        </div>
    </div>
@endsection