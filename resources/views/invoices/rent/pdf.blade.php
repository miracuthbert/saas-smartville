@component('layouts.invoice.html.layout')
    @slot('title')Invoice # {{ $leaseInvoice->hash_id }}@endslot

    {{-- Header --}}
    @slot('header')
        @component('layouts.invoice.html.header', ['url' => config('app.url')])
            {{ $leaseInvoice->property->company->name }}
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
            <tr>
                <th scope="row" class="text-left" width="50%">Property</th>
                <td class="text-right">{{ $leaseInvoice->property->name }}</td>
            </tr>
            <tr>
                <th scope="row" class="text-left" width="50%">Rent for</th>
                <td class="text-right">{{ $leaseInvoice->formattedInvoiceMonth }}</td>
            </tr>
            <tr>
                <th scope="row" class="text-left">Amount</th>
                <td class="text-right">{{ $leaseInvoice->formattedAmount }}</td>
            </tr>
            <tr>
                <th scope="row" class="text-left" width="50%">Payment due on</th>
                <td class="text-right">{{ $leaseInvoice->formattedDueAt }}</td>
            </tr>
        </table>
        @endcomponent

        {{-- Footer --}}
        @slot('footer')
        @component('layouts.invoice.html.footer')
        &copy; {{ date('Y') }} {{ $leaseInvoice->property->company->name }}. All rights reserved.
    @endcomponent
    @endslot
@endcomponent
