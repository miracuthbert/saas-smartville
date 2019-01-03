@component('layouts.invoice.html.layout')
    @slot('title')Invoice # {{ $utilityInvoice->hash_id }}@endslot

    {{-- Header --}}
    @slot('header')
        @component('layouts.invoice.html.header', ['url' => config('app.url')])
            {{ $utilityInvoice->property->company->name }}
            {{-- todo: Add Address --}}
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
            <thead>
            <tr>
                <th>Description</th>
                <th>Date</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tr>
                <td>
                    {{ $utilityInvoice->utility->name }}
                    <br>
                    Property - {{ $utilityInvoice->property->name }}

                    {{-- Show if utility varied --}}
                    @if($utilityInvoice->utility->billing_type == 'varied')
                        <br>
                        Previous reading - {{ $utilityInvoice->previous }} {{ $utilityInvoice->units }}
                        <br>
                        Current reading - {{ $utilityInvoice->current }} {{ $utilityInvoice->units }}
                    @endif
                </td>
                <td>{{ $utilityInvoice->formattedInvoiceMonth }}</td>
                <td>{{ $utilityInvoice->formattedAmount }}</td>
            </tr>
            <tr>
                <td></td>
                <th scope="row">Total</th>
                <td>{{ $utilityInvoice->formattedAmount }}</td>
            </tr>
        </table>
    @endcomponent

    <hr>

    {{-- Meta --}}
    <div>
        <div><strong>Payment due on</strong></div>
        <p>{{ $utilityInvoice->formattedDueAt }}</p>
    </div>
@endcomponent
