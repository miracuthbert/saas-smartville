@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <div class="media">
        <div class="media-body">
            <header>
                <div class="d-flex justify-content-between align-content-center">
                    <h4>Invoice Summary</h4>

                    <aside>
                        <div class="btn-group">
                            <a href="{{ route('account.invoices.utilities.pdf.download', $utilityInvoice) }}"
                               class="btn btn-outline-primary btn-sm">
                                Download
                            </a>
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
                        <td>{{ $utilityInvoice->property->company->name }}</td>
                    </tr>
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
                        <th scope="row">Sent</th>
                        <td>{{ $utilityInvoice->formattedSentAt }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Due</th>
                        <td>{{ $utilityInvoice->formattedDueAt }}</td>
                    </tr>

                    {{-- Show if utility varied --}}
                    @if($utilityInvoice->utility->billing_type == 'varied')
                        <tr>
                            <th scope="row">Previous</th>
                            <td>{{ $utilityInvoice->previous }} {{ $utilityInvoice->units }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Current</th>
                            <td>{{ $utilityInvoice->current }} {{ $utilityInvoice->units }}</td>
                        </tr>
                    @endif
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
            <div class="card">
                <div class="card-header">
                    <h4>Payments</h4>
                    <p>List of payments</p>
                </div>
                <div class="card-body">
                    @if($utilityInvoice->payments->count())
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
                                @foreach($utilityInvoice->payments as $payment)
                                    <tr>
                                        <th scope="row">{{ str_limit($payment->hash_id, 24) }}</th>
                                        <td>{{ $payment->formattedAmount }}</td>
                                        <td>{{ $payment->formattedPaidAt }}</td>
                                        <td>{{ $payment->admin->name }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                {{--<a href="{{ route('account.payments.utilities.show', $payment) }}"--}}
                                                {{--class="btn btn-outline-link btn-sm" target="_blank" role="button">View</a>--}}
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
                </div><!-- /.card-body -->
            </div><!-- /.card -->
        </div>
    </div>
@endsection