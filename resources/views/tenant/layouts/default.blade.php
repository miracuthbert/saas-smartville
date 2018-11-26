@extends('layouts.tenant.master')

@section('breadcrumb')
    @yield('tenant.breadcrumb')
@endsection

@include('tenant.layouts.partials._sidebar')

@section('content')
    @yield('tenant.stats')

    @include('layouts.partials.alerts._alerts')

    @yield('tenant.content')
@endsection