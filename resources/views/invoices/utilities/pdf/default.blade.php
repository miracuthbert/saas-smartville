@component('layouts.invoice.html.layout')
    @slot('title')Invoice # {{ $utilityInvoice->hash_id }}@endslot

    {{-- Header --}}
    @slot('header')
        @component('layouts.invoice.html.header', ['url' => config('app.url')])
            {{ $utilityInvoice->property->company->name }}
        @endcomponent
    @endslot

    {{-- Body --}}
    <div>
        <div>
            <div><strong>Invoice #:</strong></div>
            <p>{{ $utilityInvoice->hash_id }}</p>
        </div>
        <div>
            <div><strong>Date:</strong></div>
            <p>{{ $utilityInvoice->formattedSentAt }}</p>
        </div>
        <div>
            <div><strong>Name:</strong></div>
            <p>{{ $utilityInvoice->user->name }}</p>
        </div>
    </div>
    <hr>

    @component('layouts.invoice.html.table')
        <table>
            <tr>
                <th scope="row" class="text-left" width="50%">Property</th>
                <td class="text-right">{{ $utilityInvoice->property->name }}</td>
            </tr>
            <tr>
                <th scope="row" class="text-left" width="50%">Utility</th>
                <td class="text-right">{{ $utilityInvoice->utility->name }}</td>
            </tr>
            {{-- Show if utility varied --}}
            @if($utilityInvoice->utility->billing_type == 'varied')
                <tr>
                    <th scope="row">Previous</th>
                    <td class="text-right">{{ $utilityInvoice->previous }} {{ $utilityInvoice->units }}</td>
                </tr>
                <tr>
                    <th scope="row">Current</th>
                    <td class="text-right">{{ $utilityInvoice->current }} {{ $utilityInvoice->units }}</td>
                </tr>
            @endif
            <tr>
                <th scope="row" class="text-left">Amount</th>
                <td class="text-right">{{ $utilityInvoice->formattedAmount }}</td>
            </tr>
            <tr>
                <th scope="row" class="text-left" width="50%">Payment due on</th>
                <td class="text-right">{{ $utilityInvoice->formattedDueAt }}</td>
            </tr>
        </table>
    @endcomponent

    {{-- Footer --}}
    @slot('footer')
        @component('layouts.invoice.html.footer')
            &copy; {{ date('Y') }} {{ $utilityInvoice->property->company->name }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
