@component('layouts.invoice.html.layout')
    @slot('author')
        {{ $utilityPayment->property->company->name }} | {{ config('app.name') }}
    @endslot

    @slot('description')
        Receipt for utility {{ $utilityPayment->utility->name }}; Property: {{ $utilityPayment->property->name }}; Lease no.: {{ $utilityPayment->lease->hash_id }}; Generated by: {{ config('app.name') }}
    @endslot

    @slot('title')Receipt # {{ $utilityPayment->hash_id }}@endslot

    {{-- Header --}}
    @slot('header')
        @component('layouts.invoice.html.header', ['url' => config('app.url')])
            {{ $utilityPayment->property->company->name }}
        @endcomponent
    @endslot

    {{-- Body --}}
    <div>
        <div>
            <div><strong>Receipt #:</strong></div>
            <p>{{ $utilityPayment->hash_id }}</p>
        </div>
        <div>
            <div><strong>Date:</strong></div>
            <p>{{ $utilityPayment->formattedPaidAt }}</p>
        </div>
        <div>
            <div><strong>Name:</strong></div>
            <p>{{ $utilityPayment->invoice->user->name }}</p>
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
                    {{ $utilityPayment->utility->name }}
                    <br>
                    Property - {{ $utilityPayment->property->name }}
                </td>
                <td>{{ $utilityPayment->invoice->formattedInvoiceMonth }}</td>
                <td>{{ $utilityPayment->formattedAmount }}</td>
            </tr>
            <tr>
                <td></td>
                <th scope="row">Total</th>
                <td>{{ $utilityPayment->formattedAmount }}</td>
            </tr>
            </tbody>
        </table>
    @endcomponent

    <hr>

    {{-- Meta --}}
    <div>
        <div><strong>Processed by</strong></div>
        <p>{{ optional($utilityPayment->admin)->name }}</p>
    </div>
@endcomponent
