@component('mail::message')
# Tenant Access

Hello {{ $name }},

You have been added as a tenant of property: {{ $property->name }}.

You can now access and get all your invoices, receipts, post issues and get
updates via your dashboard.

Please click or copy link below to complete your profile and get started.

@component('mail::button', ['url' => route('auth.invitations.tenant.setup', [$property, $invitation])])
Get Started
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
