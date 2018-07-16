@component('mail::message')
Thanks, we got your message! We'll respond within 24 - 48 hours.


@component('mail::button', ['url' => config('app.url')])
Go to Otruvez
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent
