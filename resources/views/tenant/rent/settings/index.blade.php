@extends('tenant.layouts.default')

@section('title', page_title('Rent Settings'))

@section('tenant.breadcrumb')
    <li class="breadcrumb-item">Rent</li>
    <li class="breadcrumb-item active">Settings</li>
@endsection

@section('tenant.content')
    <rent-settings endpoint="{{ route('tenant.rent.settings.index') }}"/>
@endsection
