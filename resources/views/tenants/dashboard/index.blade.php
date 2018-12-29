@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row col-md-8 offset-md-2">
            <h4>Tenant Dashboard</h4>
            <hr>

            <div class="card border-info">
                <div class="card-header">
                    <h5 class="mt-0 mb-0">
                        Tenant Dashboard is currently under construction.
                    </h5>
                </div>
                <div class="card-body">
                    <article class="mb-3">
                        Please use your <a href="{{ route('account.dashboard') }}">Account Dashboard</a> for the time
                        being to check your invoices and payments or reporting of property related issues to your
                        landlord.
                    </article>

                    <p>
                        <strong>We will notify you when its done.</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
