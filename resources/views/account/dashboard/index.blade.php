@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <h4>My Dashboard</h4>
    <hr>

    <notifications endpoint="{{ route('account.notifications.index') }}"/>
@endsection
