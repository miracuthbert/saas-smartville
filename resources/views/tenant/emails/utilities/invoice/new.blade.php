@component('mail::message')
# {{ $property->name }}, {{ $utility->name }} Invoice

Hi {{ $user->first_name }},

***{{ $utility->name }}*** (utility) invoice for ***{{ $invoice->formattedInvoiceMonth }}*** is ready.

@component('mail::button', ['url' => $url])
View Invoice
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
