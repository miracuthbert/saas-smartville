@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <div class="mb-3">
        <h4>Lease details</h4>
        <hr>
        <div class="media d-block d-sm-flex">
            <img class="mr-3" src="{{ $lease->property->imageUrl }}" alt="property image" width="240px" height="180px">
            <div class="media-body">
                <h5 class="mt-0">{{ $lease->property->name }}</h5>

                <div class="collapse my-1 py-2" id="collapseSummary">
                    <article>{{ $lease->property->overview }}</article>
                </div>

                <p>
                    <a href="#collapseSummary" role="button" data-toggle="collapse" aria-expanded="false"
                       aria-controls="collapseSummary" id="toggleSummary">
                        Show more...
                    </a>
                </p>

                <p><strong>Rent:</strong> {{ $lease->property->formattedPrice }}</p>

                <p><strong>Moved in</strong> {{ $lease->formattedMoveIn }}</p>

                <p><strong>Moved out</strong> {{ $lease->hasVacated ? $lease->formattedMoveOut : '' }}</p>

                <p><strong>Status:</strong> {{ $lease->status }}</p>
            </div>
        </div>
        <hr>

        <!-- Invoices Section -->
        <div class="card mb-3">
            <div class="card-header">
                <h4>Rent Invoices</h4>
                <p>List of rent invoices</p>
            </div>
            <div class="card-body">
                @if($lease->rentInvoices->count())
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Due at</th>
                                <th scope="col">Outstanding Bal.</th>
                                <th scope="col">Cleared at</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lease->rentInvoices as $invoice)
                                <tr>
                                    <th scope="row">{{ str_limit($invoice->hash_id, 24) }}</th>
                                    <td>{{ $invoice->formattedAmount }}</td>
                                    <td>{{ $invoice->formattedDueAt }}</td>
                                    <td>{{ $invoice->formattedOutstandingBalance }}</td>
                                    <td>{{ $invoice->formattedClearAt }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-outline-link btn-sm" role="button"
                                               href="{{ route('account.invoices.rent.show', $invoice) }}">
                                                View
                                            </a>
                                            <a class="btn btn-outline-link btn-sm" role="button"
                                               href="{{ route('account.invoices.rent.pdf.download', $invoice) }}">
                                                Download
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>No invoices found.</p>
                @endif
            </div>
        </div>

        <!-- Payments Section -->
        <div class="card mb-3">
            <div class="card-header">
                <h4>Rent Payments</h4>
                <p>List of rent payments</p>
            </div>
            <div class="card-body">
                @if($lease->rentPayments->count())
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
                            @foreach($lease->rentPayments as $payment)
                                <tr>
                                    <th scope="row">{{ str_limit($payment->hash_id, 24) }}</th>
                                    <td>{{ $payment->formattedAmount }}</td>
                                    <td>{{ $payment->formattedPaidAt }}</td>
                                    <td>{{ $payment->admin->name }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-outline-link btn-sm" role="button" href="#">
                                                View
                                            </a>
                                            <a class="btn btn-outline-link btn-sm" role="button"
                                               href="{{ route('account.payments.rent.pdf.download', $payment) }}">
                                                Download
                                            </a>
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
    </div>
@endsection

@section('scripts')
    <script>
        $('#collapseSummary').on('hidden.bs.collapse', function () {
            $('#toggleSummary').text('Show more...')
        })

        $('#collapseSummary').on('shown.bs.collapse', function () {
            $('#toggleSummary').text('Show less')
        })
    </script>
@endsection