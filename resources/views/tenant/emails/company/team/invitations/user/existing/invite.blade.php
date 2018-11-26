@component('mail::message')
# Team Member

You have been added as a member of {{ $company->name }}.

Click button or link below to access the company's dashboard.

@component('mail::button', ['url' => route('tenant.switch', $company)])
Go to Dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
