@component('mail::message')

{{ $message }}

From,
{{ $name }},
{{ $email }}

@component('mail::button', ['url' => url('/')])
View this in App
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
