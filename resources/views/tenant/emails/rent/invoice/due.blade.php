@component('mail::message')
# {{ $property->name }} Rent Due Reminder

Hi {{ $user->first_name }},

The rent for {{ $leaseInvoice->formattedInvoiceMonth }} is due by: {{ $leaseInvoice->formattedDueAt }} ({{ $dueDate }}).

Please clear your balance or notify us if payment has been made.

@component('mail::button', ['url' => $url])
View Invoice
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
