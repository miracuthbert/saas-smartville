@component('layouts.invoice.html.layout')
    @slot('title')Invoice # {{ $leaseInvoice->hash_id }}@endslot

    {{-- Header --}}
    @slot('header')
        @component('layouts.invoice.html.header', ['url' => config('app.url')])
            {{ $leaseInvoice->property->company->name }}
            {{-- todo: Add Address --}}
        @endcomponent
    @endslot

    {{-- Body --}}
    <div>
        <div>
            <div><strong>Invoice #:</strong></div>
            <p>{{ $leaseInvoice->hash_id }}</p>
        </div>
        <div>
            <div><strong>Date:</strong></div>
            <p>{{ $leaseInvoice->formattedSentAt }}</p>
        </div>
        <div>
            <div><strong>Name:</strong></div>
            <p>{{ $leaseInvoice->user->name }}</p>
        </div>
    </div>
    <hr>

    @component('layouts.invoice.html.table')
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    {{ $leaseInvoice->property->name }} - (Property rent)
                </td>
                <td>{{ $leaseInvoice->formattedInvoiceMonth }}</td>
                <td>{{ $leaseInvoice->formattedAmount }}</td>
            </tr>
            <tr>
                <td></td>
                <th scope="row">Total</th>
                <td>{{ $leaseInvoice->formattedAmount }}</td>
            </tr>
            </tbody>
        </table>
    @endcomponent

    <hr>

    {{-- Meta --}}
    <div>
        <div><strong>Payment due on</strong></div>
        <p>{{ $leaseInvoice->formattedDueAt }}</p>
    </div>
@endcomponent
