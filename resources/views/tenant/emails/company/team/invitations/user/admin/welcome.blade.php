@component('mail::message')
# Admin Role Privileges

Hi {{ $user->first_name }},

You have been given _Administrator_ privileges in company:__{{ $company->name }}__ when it was created.

@component('mail::panel')
You now can:
* Setup roles and permissions
* Add team members
* Update company profile
* Manage properties, leases, invoices and many more
@endcomponent

@component('mail::button', ['url' => route('tenant.switch', $company), 'button' => 'green'])
Get Started
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
