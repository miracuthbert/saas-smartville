@component('mail::message')
# {{ $property->name }}, {{ $utility->name }} Payment

Hi {{ $user->first_name }},

***{{ $utility->name }}*** (utility) payment for ***{{ $invoice->formattedInvoiceMonth }}*** received.

***Invoice has been cleared.***

@component('mail::button', ['url' => $url])
View Invoice
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
