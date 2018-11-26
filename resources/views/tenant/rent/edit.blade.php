@extends('tenant.layouts.default')

@section('title', page_title("Edit Rent Invoice"))

@section('tenant.breadcrumb')
    <li class="breadcrumb-item">Rent</li>
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.rent.invoices.index') }}">Invoices</a>
    </li>
    <li class='breadcrumb-item active'>Edit Invoice</li>
@endsection

@section('tenant.content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-content-center">
                <h4 class="card-title">
                    Edit Rent Invoice
                </h4>

                <aside>
                    <div class="btn-group">
                        <a href="{{ route('tenant.rent.invoices.show', $leaseInvoice) }}" class="btn btn-link">
                            View
                        </a>
                        <a href="{{ route('tenant.rent.invoices.preview', $leaseInvoice) }}" class="btn btn-link"
                           target="_blank">
                            Preview
                        </a>
                    </div>
                </aside>
            </div>

            <form method="POST" action="{{ route('tenant.rent.invoices.update', $leaseInvoice) }}">
                @csrf
                @method('PUT')

                <div class="form-group row">
                    <label for="property_id" class="col-md-4 control-label">Property</label>

                    <div class="col-md-6">
                        <input type="text" id="property_id" class="form-control-plaintext"
                               value="{{ $leaseInvoice->property->name }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="tenant" class="col-md-4 control-label">Tenant</label>

                    <div class="col-md-6">
                        <input type="text" id="tenant" class="form-control-plaintext"
                               value="{{ $leaseInvoice->user->name }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="amount" class="col-md-4 control-label">Amount</label>

                    <div class="col-md-6">
                        <input type="text" id="amount" class="form-control-plaintext"
                               value="{{ $leaseInvoice->formattedAmount }}" readonly>

                        <small class="form-text text-muted">Taken from the property price during invoice generation.
                        </small>
                    </div>
                </div>

                @if(now()->lt($leaseInvoice->sent_at))
                    @datepicker([
                        'field_name' => 'start_at',
                        'required' => true,
                        'label' => 'From',
                        'description' => 'Start date for rent invoice.',
                        'value' => $leaseInvoice->start_at
                    ])
                    @enddatepicker

                    @datepicker([
                        'field_name' => 'end_at',
                        'required' => true,
                        'label' => 'To',
                        'description' => 'End date for rent invoice.',
                        'value' => $leaseInvoice->end_at
                    ])
                    @enddatepicker

                    @datetimepicker([
                        'field_name' => 'sent_at',
                        'required' => true,
                        'label' => 'Send at',
                        'description' => 'Date invoice should be sent to tenant.',
                        'value' => $leaseInvoice->sent_at
                    ])
                    @enddatetimepicker

                    @datetimepicker([
                        'field_name' => 'due_at',
                        'required' => true,
                        'label' => 'Due at',
                        'description' => 'Date payment due.',
                        'value' => $leaseInvoice->due_at
                    ])
                    @enddatetimepicker

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="send_invoice" class="custom-control-input"
                                       id="send_invoice"
                                       value="1">
                                <label class="custom-control-label" for="send_invoice">Send invoice to
                                    tenant immediately</label>
                            </div>

                            <small class="form-text text-muted">
                                Send at date above will be ignored.
                            </small>
                        </div>
                    </div>
                @endif

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection