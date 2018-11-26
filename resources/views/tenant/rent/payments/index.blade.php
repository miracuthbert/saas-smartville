@extends('tenant.layouts.default')

@section('title', page_title("Rent Invoice | Payment History"))

@section('tenant.breadcrumb')
    <li class="breadcrumb-item">Rent</li>
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.rent.invoices.index') }}">Invoices</a>
    </li>
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.rent.invoices.show', $leaseInvoice) }}">{{ $leaseInvoice->hash_id }}</a>
    </li>
    <li class='breadcrumb-item active'>Payment History</li>
@endsection

@section('tenant.content')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <h4>Invoice Payment History</h4>

                    <aside>
                        <div class="btn-group" role="group">
                            @if(!$leaseInvoice->cleared_at)
                                <a href="{{ route('tenant.rent.invoices.remind', $leaseInvoice) }}" class="btn btn-link btn-sm">Send Reminder</a>
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
            <hr>

            <div class="media">
                <div class="media-body">
                    <article>
                        <span>Rent for</span>
                        <strong>{{ $leaseInvoice->formattedInvoiceMonth }}</strong>
                    </article>
                    <article>
                        <span>Property</span>
                        <strong>{{ $leaseInvoice->property->name }}</strong>
                    </article>
                    <article>
                        <span>Tenant</span>
                        <strong>{{ $leaseInvoice->user->name }}</strong>
                    </article>
                    <article>
                        <span>Due</span>
                        <strong>{{ $leaseInvoice->formattedDueAt }}</strong>
                    </article>
                    <article>
                        <span>Cleared On</span>
                        <strong>{{ $leaseInvoice->formattedClearedAt }}</strong>
                    </article>
                </div>
            </div>
            <hr>

            @if($payments->count())
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
                        @foreach($payments as $payment)
                            <tr>
                                <th scope="row">{{ str_limit($payment->hash_id, 24) }}</th>
                                <td>{{ $payment->formattedAmount }}</td>
                                <td>{{ $payment->formattedPaidAt }}</td>
                                <td>{{ $payment->admin->name }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('tenant.rent.invoices.payments.show', [$leaseInvoice, $payment]) }}"
                                           class="btn btn-outline-link btn-sm" target="_blank" role="button">View</a>
                                        <a href="{{ route('account.payments.rent.pdf.download', $payment) }}"
                                           class="btn btn-outline-primary btn-sm" role="button">Download</a>
                                        <a href="{{ route('tenant.rent.invoices.payments.destroy', [$leaseInvoice, $payment]) }}"
                                           class="btn btn-outline-danger btn-sm" role="button">Cancel</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        <!-- Amount Charged -->
                        <tr>
                            <th scope="row">Amount Charged</th>
                            <th scope="row" colspan="3">{{ $leaseInvoice->formattedAmount }}</th>
                        </tr>
                        <!-- Amount Paid -->
                        <tr>
                            <th scope="row">Amount Paid</th>
                            <th scope="row" colspan="3">{{ $leaseInvoice->formattedPaymentTotal }}</th>
                        </tr>
                        <!-- Overdue Bal. -->
                        <tr>
                            <th scope="row">Overdue Bal.</th>
                            <th scope="row" colspan="3">{{ $leaseInvoice->formattedOutstandingBalance }}</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <p>No payments found.</p>
            @endif
        </div>
    </div>
@endsection