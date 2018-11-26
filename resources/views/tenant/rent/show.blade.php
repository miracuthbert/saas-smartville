@extends('tenant.layouts.default')

@section('title', page_title("Rent Invoice Summary"))

@section('tenant.breadcrumb')
    <li class="breadcrumb-item">Rent</li>
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.rent.invoices.index') }}">Invoices</a>
    </li>
    <li class='breadcrumb-item'>{{ $leaseInvoice->hash_id }}</li>
    <li class='breadcrumb-item active'>Invoice Summary</li>
@endsection

@section('tenant.content')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <h4>Invoice Summary</h4>

                    <aside>
                        <div class="btn-group" role="group">
                            @if(!$leaseInvoice->cleared_at)
                                <a href="{{ route('tenant.rent.invoices.remind', $leaseInvoice) }}" class="btn btn-link btn-sm">
                                    Send Reminder
                                </a>
                                <a href="{{ route('tenant.rent.invoices.clear', $leaseInvoice) }}" class="btn btn-link btn-sm">
                                    Issue Receipt
                                </a>
                            @endif
                            <a href="{{ route('tenant.rent.invoices.preview', $leaseInvoice) }}" class="btn btn-link btn-sm"
                               target="_blank">
                                Preview
                            </a>
                            <a href="{{ route('account.invoices.rent.pdf.download', $leaseInvoice) }}"
                               class="btn btn-outline-primary btn-sm">Download</a>
                        </div>
                    </aside>
                </div>
            </div>

            <!-- Invoice Section -->
            <div class="table-responsive-sm">
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <th scope="row">Invoice #</th>
                        <td>{{ $leaseInvoice->hash_id }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Rent for</th>
                        <td>{{ $leaseInvoice->formattedInvoiceMonth }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Property</th>
                        <td>{{ $leaseInvoice->property->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Tenant</th>
                        <td>{{ $leaseInvoice->user->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Amount</th>
                        <td>{{ $leaseInvoice->formattedAmount }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Sent</th>
                        <td>{{ $leaseInvoice->formattedSentAt }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Due</th>
                        <td>{{ $leaseInvoice->formattedDueAt }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Outstanding bal.</th>
                        <td>{{ $leaseInvoice->formattedOutstandingBalance }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Cleared on</th>
                        <td>{{ $leaseInvoice->formattedClearedAt }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <hr>

            <!-- Payments Section -->
            <div class="d-flex justify-content-between align-content-center">
                <div>
                    <h4>Payments</h4>
                    <p>List of payments</p>
                </div>

                <aside>
                    <a href="{{ route('tenant.rent.invoices.payments.index', $leaseInvoice) }}" class="btn btn-link btn-sm">
                        View detailed payment history
                    </a>
                </aside>
            </div>

            @if($leaseInvoice->payments->count())
                <div class="table-responsive-sm">
                    <table class="table table-hover table-borderless">
                        <thead>
                        <tr>
                            <th scope="col">Amount</th>
                            <th scope="col">Paid at</th>
                            <th scope="col">Processed by</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($leaseInvoice->payments as $payment)
                            <tr>
                                <td>{{ $payment->formattedAmount }}</td>
                                <td>{{ $payment->formattedPaidAt }}</td>
                                <td>{{ $payment->admin->name }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('tenant.rent.invoices.payments.show', [$leaseInvoice, $payment]) }}"
                                           class="btn btn-outline-link btn-sm" target="_blank" role="button">View</a>
                                        <a href="{{ route('account.payments.rent.pdf.download', $payment) }}"
                                           class="btn btn-outline-primary btn-sm" role="button">Download</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No payments found.</p>
            @endif
        </div>
    </div>
@endsection