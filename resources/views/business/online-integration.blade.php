@extends('layouts.app')
@section('body')
    @include('partials.business-back')
    <h3 class="text-center">Online integration</h3>
    <div class="col-md-8 offset-md-2 p-3" >
        <p>1) Add the button below to your website to route your customer to Otruvez so that they can purchase the subscription to your service</p>
        <div class="text-center p-3">
            <button class="btn" style="background: #4cb996 !important; color: whitesmoke !important;">Subscribe</button>
        </div>
        <pre id="button-code" style=" background-color: lightgray" class="p-2">
{{{ '<button class="btn" style="background: #4cb996 !important; color: whitesmoke !important;">Subscribe</button>' }}}
        </pre>
        <h4 class="copy theme-color text-center" data-target-copy="#button-code">click here to copy the code above <span class="fa fa-copy"></span></h4>
        <hr>
    </div>

    <div class="col-md-8 offset-md-2 p-3" >
        <p>2) After your customer subscribes, they will need a place to go. Please provide us with a url for your customer to be redirected to after they've signed up</p>
        <div class="text-center p-3">
            <form>
                <span>https://<input style="width: 80%; height: 40px" placeholder="www.example.com/thanks" type="text"></span>
                <button type="button" class="btn theme-background m-3">Save redirect url</button>
            </form>
        </div>
        <hr>
    </div>

    <div class="col-md-8 offset-md-2 p-3">
        <h4 class="text-center">API calls</h4>
        <p>3) Add the button below to your website to route your customer to Otruvez so that they can purchase the subscription to your service</p>
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
    </div>
@endsection