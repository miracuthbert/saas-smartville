@component('mail::message')
# Hello,

_{{ $user->name }}_ has posted an issue about: _{{ $issue->title }}.

@component('mail::button', ['url' => $url])
View Issue
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
