@component('mail::message')
# {{ $property->name }}, {{ $utility->name }} Payment Due Reminder

Hi {{ $user->first_name }},

***{{ $utility->name }}*** (utility) payment for ***{{ $invoice->formattedInvoiceMonth }}*** was expected by: {{ $invoice->formattedDueAt }} ({{ $dueDate }}).

The expected balance has not been cleared yet.

Please clear your balance or notify us if payment has been made.

@component('mail::button', ['url' => $url])
View Invoice
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
