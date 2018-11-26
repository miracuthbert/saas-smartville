@component('mail::message')
# Team Member

You have been added as a member of {{ $company->name }}.

Click button or link below to sign up and complete profile
to access the company's dashboard.

@component('mail::button', ['url' => route('auth.invitations.company.setup', [$company, $invitation])])
Get Started
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
