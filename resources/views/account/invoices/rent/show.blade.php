@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <div class="media">
        <div class="media-body">
            <header>
                <div class="d-flex justify-content-between align-content-center">
                    <h4>Invoice Summary</h4>

                    <aside>
                        <div class="btn-group">
                            <a href="{{ route('account.invoices.rent.pdf.download', $leaseInvoice) }}"
                               class="btn btn-outline-primary btn-sm">Download</a>
                        </div>
                    </aside>
                </div>
            </header>

            <!-- Invoice Section -->
            <div class="table-responsive">
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <th scope="row">Company</th>
                        <td>{{ $leaseInvoice->property->company->name }}</td>
                    </tr>
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
                        <th scope="row">Sent</th>
                        <td>{{ $leaseInvoice->formattedSentAt }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Due</th>
                        <td>{{ $leaseInvoice->formattedDueAt }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Amount</th>
                        <td>{{ $leaseInvoice->formattedAmount }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Paid bal.</th>
                        <td>{{ $leaseInvoice->formattedPaymentTotal }}</td>
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
            <h4>Payments</h4>
            <p>List of payments</p>

            @if($leaseInvoice->payments->count())
                <div class="table-responsive">
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
                        @foreach($leaseInvoice->payments as $payment)
                            <tr>
                                <th scope="row">{{ str_limit($payment->hash_id, 24) }}</th>
                                <td>{{ $payment->formattedAmount }}</td>
                                <td>{{ $payment->formattedPaidAt }}</td>
                                <td>{{ $payment->admin->name }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        {{--<a href="{{ route('account.payments.rent.show', $payment) }}"--}}
                                        {{--class="btn btn-outline-link btn-sm" target="_blank" role="button">View</a>--}}
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