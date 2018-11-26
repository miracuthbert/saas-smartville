@component('layouts.reports.html.layout')
    @slot('title'){{ $title }}@endslot

    {{-- Header --}}
    @slot('header')
        @component('layouts.reports.html.header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
    <section class="content">
        <h3>{{ $title }}</h3>
        <p class="sub">Generated on: {{ $date }}</p>
    </section>

    <section class="content">
        <h6>Invoice status types</h6>
        <ul class="legend">
            <li><span class="badge legend badge-light">&nbsp;</span> Draft</li>
            <li><span class="badge legend badge-info">&nbsp;</span> Sent</li>
            <li><span class="badge legend badge-warning">&nbsp;</span> Due within 1-7 days</li>
            <li><span class="badge legend badge-danger">&nbsp;</span> Past Due</li>
            <li><span class="badge legend badge-success">&nbsp;</span> Cleared</li>
        </ul>
    </section>
    <hr>

    @component('layouts.reports.html.table')
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Property</th>
                <th>Utility</th>
                <th>For</th>
                <th>Amt.</th>
                {{-- todo: Create settings to hold company utilities invoice field preferences --}}
                {{--<th>Sent</th>--}}
                <th>Due</th>
                {{--<th>Bal.</th>--}}
                <th>Cleared</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoices as $invoice)
                <tr class="{{ invoiceContext($invoice, 'table') }}">
                    <td>{{ $invoice->user->name }}</td>
                    <td>{{ $invoice->property->name }}</td>
                    <td>{{ $invoice->utility->name }}</td>
                    <td>{{ $invoice->formattedInvoiceMonth }}</td>
                    <td>{{ $invoice->formattedAmount }}</td>
                    {{-- todo: Create settings to hold company utilities invoice field preferences --}}
                    {{--<td>{{ $invoice->formattedSentAt }}</td>--}}
                    <td>{{ $invoice->formattedDueAt }}</td>
                    {{--<td>{{ $invoice->formattedOutstandingBalance }}</td>--}}
                    <td>{{ $invoice->formattedClearedAt }}</td>
                    <td>{{ $invoice->status }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @endcomponent

        {{-- Footer --}}
        @slot('footer')
        @component('layouts.reports.html.footer')
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    @endcomponent
    @endslot
@endcomponent
