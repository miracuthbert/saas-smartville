@component('mail::message')
# {{ $heading }}

Hi {{ $invitation->name }},

@if($invitation->type == 'user_invitation')
Checkout this real estate management app and get a free one month trial.

Please let us know if it meets your basic requirements.

***It is currently open for beta testing. So only basic features are currently available.***
@else
You have been added as an Admin, please complete the sign up process within six hours.
@endif

@component('mail::button', ['url' => config('app.url')])
Get Started
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
