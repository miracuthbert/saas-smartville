@extends('tenant.layouts.default')

@section('title', page_title("Setup Utility Invoice"))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.utilities.index') }}">Utilities</a>
    </li>
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.utilities.invoices.index') }}">Invoices</a>
    </li>
    <li class='breadcrumb-item active'>Setup Invoice</li>
@endsection

@section('tenant.content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                Setup Utility Invoice
            </h4>

            <form method="POST" action="{{ route('tenant.utilities.invoices.preset') }}">
                @csrf

                @include('tenant.utilities.partials.form._utilities')

                @include('components._datepicker', [
                    'field_name' => 'start_at',
                    'required' => true,
                    'label' => 'From',
                    'description' => 'Start date for rent invoice.'
                ])

                @include('components._datepicker', [
                    'field_name' => 'end_at',
                    'required' => true,
                    'label' => 'To',
                    'description' => 'End date for rent invoice.'
                ])

                @include('components._datetimepicker', [
                    'field_name' => 'sent_at',
                    'required' => true,
                    'label' => 'Send at',
                    'description' => 'Date invoice should be sent to tenants.'
                ])

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Next</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection