@component('mail::message')
# {{ $property->name }} Rent Payment

Hi {{ $invoice->user->first_name }},

***{{ $invoice->formattedInvoiceMonth }}*** rent payment has been received.

***Invoice has been cleared.***

@component('mail::button', ['url' => $url])
View Invoice
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
