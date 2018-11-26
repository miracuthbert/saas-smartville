@component('mail::message')
# {{ $property->name }} Rent Due Reminder

Hi {{ $user->first_name }},

The rent for {{ $invoice->formattedInvoiceMonth }} was expected by: {{ $invoice->formattedDueAt }} ({{ $dueDate }}).

The expected balance has not been cleared yet.

Please clear your balance or notify us if payment has been made.

@component('mail::button', ['url' => $url])
View Invoice
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
