@component('mail::message')
# Tenant Access

Hello {{ $user->first_name }},

You have been added as a tenant of property: {{ $property->name }}.

You can now access and get all your invoices, receipts, post issues and get
updates via your dashboard.

Please click or copy link below to access your property dashboard.

@component('mail::button', ['url' => route('account.dashboard')])
Go to Dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
