@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <header>
        <h4>Utility Invoices</h4>
        <p>List of utility invoices.</p>
    </header>
    <hr>

    <section>
        @if($invoices->count())
            <div class="table-responsive-sm">
                <table class="table table-hover table-borderless">
                    <thead>
                    <tr>
                        <th scope="col">Property</th>
                        <th scope="col">Utility</th>
                        <th scope="col">For</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Due at</th>
                        <th scope="col">O &sol; Bal.</th>
                        <th scope="col">Cleared at</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->property->name }}</td>
                            <td>{{ $invoice->utility->name }}</td>
                            <td>{{ $invoice->formattedInvoiceMonth }}</td>
                            <td>{{ $invoice->formattedAmount }}</td>
                            <td>{{ $invoice->formattedDueAt }}</td>
                            <td>{{ $invoice->formattedOutstandingBalance }}</td>
                            <td>{{ $invoice->formattedClearedAt }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a class="btn btn-outline-link btn-sm" role="button"
                                       href="{{ route('account.invoices.utilities.show', $invoice) }}">View</a>
                                    <a class="btn btn-outline-primary btn-sm" role="button"
                                       href="{{ route('account.invoices.utilities.pdf.download', $invoice) }}">Download</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            {{ $invoices->links() }}
        @else
            <p>No invoices found.</p>
        @endif
    </section>
@endsection