@extends('tenant.layouts.default')

@section('title', page_title("Process Utility Invoice"))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.utilities.index') }}">Utilities</a>
    </li>
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.utilities.invoices.index') }}">Invoices</a>
    </li>
    <li class="breadcrumb-item">{{ $utilityInvoice->hash_id }}</li>
    <li class='breadcrumb-item active'>Process Invoice</li>
@endsection

@section('tenant.content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-content-center">
                <h4 class="card-title">
                    Process Utility Invoice
                </h4>

                <aside>
                    <div class="btn-group">
                        <a href="{{ route('tenant.utilities.invoices.show', $utilityInvoice) }}" class="btn btn-link">
                            View
                        </a>
                        <a href="#" class="btn btn-link"
                           target="_blank">
                            Preview
                        </a>
                    </div>
                </aside>
            </div>

            <form method="POST" action="{{ route('tenant.utilities.invoices.clear', $utilityInvoice) }}">
                @csrf
                @method('PUT')

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
                    <label for="invoice_amount" class="col-md-4 control-label">Amount</label>

                    <div class="col-md-6">
                        <input type="text" id="invoice_amount" class="form-control-plaintext"
                               value="{{ $utilityInvoice->formattedAmount }}" readonly>

                        <small class="form-text text-muted">Taken from the property price during invoice generation.
                        </small>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="invoice_out_bal" class="col-md-4 control-label">Outstanding bal.</label>

                    <div class="col-md-6">
                        <input type="text" id="invoice_out_bal" class="form-control-plaintext"
                               value="{{ $utilityInvoice->formattedOutstandingBalance }}" readonly>

                        <small class="form-text text-muted">The remaining invoice balance.</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="sent_at" class="col-md-4 control-label">Sent at</label>

                    <div class="col-md-6">
                        <input type="text" id="sent_at" class="form-control-plaintext"
                               value="{{ $utilityInvoice->formattedSentAt }}" readonly>

                        <small class="form-text text-muted">Date invoice was sent to tenant.</small>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('amount') ? ' has-error' : '' }}">
                    <label for="amount" class="col-md-4 control-label">Amount paid</label>

                    <div class="col-md-6">
                        <price-input name="amount"
                                     id="amount"
                                     value="{{ old('amount') }}"
                                     error="{{ $errors->first('amount') }}"
                                     currency="{{ $utilityInvoice->currency }}"
                                     required="true" autofocus="true"></price-input>

                        <small class="form-text text-muted">Amount paid by tenant. Used for receipt generation.</small>
                    </div>
                </div>

                @include('components._datetimepicker', [
                    'field_name' => 'paid_at',
                    'required' => true,
                    'label' => 'Paid at',
                    'description' => 'Date payment made.'
                ])

                @include('tenant.partials.forms._payment_methods', [
                    'description' => 'The payment method used.'
                ])

                <div class="form-group row{{ $errors->has('description') ? ' has-error' : '' }}">
                    <label for="description" class="control-label col-md-4">Description</label>
                    <div class="col-md-6">
                        <textarea name="description" id="description" rows="5"
                                  class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}">{{ old('description') }}</textarea>

                        @if($errors->has('description'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('description') }}</strong>
                            </div>
                        @endif

                        <small class="form-text text-muted">Any extra details about payment.</small>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection