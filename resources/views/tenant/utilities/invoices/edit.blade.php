@extends('tenant.layouts.default')

@section('title', page_title("Edit Utility Invoice"))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.utilities.index') }}">Utilities</a>
    </li>
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.utilities.invoices.index') }}">Invoices</a>
    </li>
    <li class="breadcrumb-item">{{ $utilityInvoice->hash_id }}</li>
    <li class='breadcrumb-item active'>Edit Invoice</li>
@endsection

@section('tenant.content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                Edit Utility Invoice
            </h4>

            <form method="POST" action="{{ route('tenant.utilities.invoices.update', $utilityInvoice) }}">
                @csrf
                @method('PUT')

                <div class="form-group row">
                    <label for="utility_id" class="col-md-4 control-label">Utility</label>

                    <div class="col-md-6">
                        <input type="hidden" name="utility_id" value="{{ $utilityInvoice->utility->id }}">
                        <input type="hidden" name="billing_type" value="{{ $utilityInvoice->utility->billing_type }}">

                        <input type="text" id="utility_id" class="form-control-plaintext"
                               value="{{ $utilityInvoice->utility->name }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="property_id" class="col-md-4 control-label">Property</label>

                    <div class="col-md-6">
                        <input type="text" id="property_id" class="form-control-plaintext"
                               value="{{ $utilityInvoice->property->name }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="tenant" class="col-md-4 control-label">Tenant</label>

                    <div class="col-md-6">
                        <input type="text" id="tenant" class="form-control-plaintext"
                               value="{{ $utilityInvoice->user->name }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="invoice_for" class="col-md-4 control-label">For</label>

                    <div class="col-md-6">
                        <input type="hidden" name="start_at" value="{{ $utilityInvoice->start_at }}">
                        <input type="hidden" name="end_at" value="{{ $utilityInvoice->end_at }}">

                        <input type="text" id="invoice_for" class="form-control-plaintext"
                               value="{{ $utilityInvoice->formattedInvoiceMonth }}"
                               readonly>
                    </div>
                </div>

                @include('components._datetimepicker', [
                    'field_name' => 'sent_at',
                    'required' => true,
                    'label' => 'Send at',
                    'description' => 'Date invoice should be sent to tenants.',
                    'value' => $utilityInvoice->sent_at
                ])

                <div class="form-group row">
                    <label for="invoice_due" class="col-md-4 control-label">Due</label>

                    <div class="col-md-6">
                        <input type="hidden" name="due_at" value="{{ $utilityInvoice->due_at }}">

                        <input type="text" id="invoice_due" class="form-control-plaintext"
                               value="{{ $utilityInvoice->formattedDueAt }}" readonly>

                        <small class="form-text text-muted">
                            Due date is auto generated based on utility and send date.
                        </small>
                    </div>
                </div>

                @if($utilityInvoice->utility->billing_type == 'varied')
                    <div class="form-group row">
                        <label for="previous" class="col-md-4 control-label">Previous</label>

                        <div class="col-md-6">
                            <input type="text" name="previous" id="previous" class="form-control"
                                   value="{{ old('previous', $utilityInvoice->previous) }}">

                            <small class="form-text text-muted">
                                The previous billing units.
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="current" class="col-md-4 control-label">Current</label>

                        <div class="col-md-6">
                            <input type="text" name="current" id="current" class="form-control"
                                   value="{{ old('current', $utilityInvoice->current) }}">

                            <small class="form-text text-muted">
                                The current billing units.
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="amount" class="col-md-4 control-label">Amount</label>

                        <div class="col-md-6">
                            <input type="hidden" id="price" value="{{ $utilityInvoice->price }}">
                            <input type="hidden" id="currency" value="{{ $utilityInvoice->currency }}">

                            <input type="text" id="amount" class="form-control-plaintext"
                                   value="{{ $utilityInvoice->formattedAmount }}" readonly>

                            <small class="form-text text-muted">
                                The billing amount. Calculated from current and previous.
                            </small>
                        </div>
                    </div>
                @endif

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var $price = $('input#price').val();
        var $currency = $('input#currency').val();
        var $current = $('input#current');
        var $prev = $('input#previous');
        var $amount = $('input#amount');

        var amount = function (current = $current.val(), previous = $prev.val()) {
            var calculated = (current - previous) * $price;
            return parseFloat(calculated).toFixed(2) || 0;
        }

        $("input#previous, input#current").on('change keydown keyup', function (event) {
            var $total = 0;

            if (($total = amount())) {
                $amount.val($currency + ' ' + $total);
            }
        });
    </script>
@endpush