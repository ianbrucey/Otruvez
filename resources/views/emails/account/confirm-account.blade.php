@component('mail::message')
<b>Hi, {{$name}}.</b>

Thank you for registering. Please go back and enter the 6 digit code below to confirm your account
<br>
<h1>{{$token}}</h1>

@component('mail::button')
Thanks for choosing Otruvez
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent
