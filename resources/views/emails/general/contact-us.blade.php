@component('mail::message')
<b>Incoming message from {!! $emailAddress[0]['address'] !!}</b>

<p>{{$text}}</p>


Thanks,
{{ config('app.name') }}
@endcomponent
