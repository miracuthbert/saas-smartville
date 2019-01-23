@extends('tenant.layouts.default')

@section('title', page_title('Utilities Settings'))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.utilities.index') }}">Utilities</a>
    </li>
    <li class='breadcrumb-item active'>Settings</li>
@endsection

@section('tenant.content')
    <utilities-settings endpoint="{{ route('tenant.utilities.settings.index') }}"/>
@endsection
