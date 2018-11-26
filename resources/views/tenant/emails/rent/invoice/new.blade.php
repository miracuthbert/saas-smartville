@component('mail::message')
# {{ $property->name }} Rent Invoice

Hi {{ $user->first_name }},

The rent invoice for ***{{ $leaseInvoice->formattedInvoiceMonth }}*** is ready.

@component('mail::button', ['url' => $url])
View Invoice
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
