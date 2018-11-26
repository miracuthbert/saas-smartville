@extends('tenant.layouts.default')

@section('title', page_title("Utility Invoice Summary"))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.utilities.index') }}">Utilities</a>
    </li>
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.utilities.invoices.index') }}">Invoices</a>
    </li>
    <li class="breadcrumb-item">{{ $utilityInvoice->hash_id }}</li>
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
                            @if(!$utilityInvoice->cleared_at)
                                <a href="{{ route('tenant.utilities.invoices.remind', $utilityInvoice) }}"
                                   class="btn btn-link btn-sm">
                                    Send Reminder
                                </a>
                                <a href="{{ route('tenant.utilities.invoices.clear', $utilityInvoice) }}"
                                   class="btn btn-link btn-sm">
                                    Issue Receipt
                                </a>
                            @endif

                            <a href="{{ route('account.invoices.utilities.preview', $utilityInvoice) }}"
                               class="btn btn-link btn-sm" target="_blank">
                                Preview
                            </a>
                            <a href="{{ route('account.invoices.utilities.pdf.download', $utilityInvoice) }}"
                               class="btn btn-outline-primary btn-sm">
                                Download
                            </a>
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
                        <td>{{ $utilityInvoice->hash_id }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Utility</th>
                        <td>{{ $utilityInvoice->utility->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">For</th>
                        <td>{{ $utilityInvoice->formattedInvoiceMonth }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Property</th>
                        <td>{{ $utilityInvoice->property->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Tenant</th>
                        <td>{{ $utilityInvoice->user->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Sent</th>
                        <td>{{ $utilityInvoice->formattedSentAt }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Due</th>
                        <td>{{ $utilityInvoice->formattedDueAt }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Amount</th>
                        <td>{{ $utilityInvoice->formattedAmount }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Paid bal.</th>
                        <td>{{ $utilityInvoice->formattedPaymentTotal }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Outstanding bal.</th>
                        <td>{{ $utilityInvoice->formattedOutstandingBalance }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Cleared on</th>
                        <td>{{ $utilityInvoice->formattedClearedAt }}</td>
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
                    <a href="{{ route('tenant.utilities.invoices.payments.index', $utilityInvoice) }}"
                       class="btn btn-link btn-sm">
                        View detailed payment history
                    </a>
                </aside>
            </div>

            @if($utilityInvoice->payments->count())
                <div class="table-responsive-sm">
                    <table class="table table-hover table-borderless">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Paid at</th>
                            <th scope="col">Processed by</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($utilityInvoice->payments as $payment)
                            <tr>
                                <th scope="row">{{ str_limit($payment->hash_id, 24) }}</th>
                                <td>{{ $payment->formattedAmount }}</td>
                                <td>{{ $payment->formattedPaidAt }}</td>
                                <td>{{ $payment->admin->name }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('account.payments.utilities.pdf.download', $payment) }}"
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
