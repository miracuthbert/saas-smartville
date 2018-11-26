@component('mail::message')
# Hello,

Thank you for signing up on our platform [{{ config('app.name') }}]({{ url('/') }}).

You are receiving this email because it has been setup as the primary email for the company:
**{{ $company->name }}**.

@component('mail::panel')
To get the best out of our platform:
* Visit our [documentation]({{ url('/documentation') }} "Documentation") section or
* Contact our [support]({{ url('/support') }} "Support") team whenever you need help.
@endcomponent

_We wish you all the best and hope our platform helps you serve your clients better._

Use the link below to login and access your company dashboard.

@component('mail::button', ['url' => route('tenant.switch', $company), 'color' => 'green'])
Get Started
@endcomponent

*If you have received this email by mistake*:
Please contact our **[support]({{ url('/support') }} "Support")** team immediately.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
