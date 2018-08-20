@extends('layouts.app')
@section('body')
    @include('partials.business-back')
    <h3 class="text-center"> API Setup</h3>

    <div class="text-center">
        <button class="btn theme-background" data-toggle="collapse" data-target="#showapikey">Show API Key</button>
    </div>

    <div id="showapikey" class="collapse"><hr>
        <p class="text-center">{{$business->api_key}} </p>
        <h3 class="text-center"> <span class="fa fa-copy"></span> copy</h3>
    </div>

    <div class="col-md-8 offset-md-2 p-3">
        <p class="">sample request</p>
        <pre style=" background-color: lightgray" class="p-3">
{
    'api_key': '{{$business->api_key}}',
    'business_id': '{{$business->id}}',
    'subscription_id: 'sub_id',
    'user_email: 'user_email'
}
        </pre>
    </div>
@endsection