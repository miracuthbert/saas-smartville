@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <div>
        <notifications endpoint="{{ route('account.notifications.index') }}"/>
    </div>
@endsection
