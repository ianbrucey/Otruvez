@extends('layouts.app')
@section('body')
    @include('partials.business-back')
    <h2 class="text-center">Online payment set up for <span class="theme-color">{{$plan->stripe_plan_name}}</span></h2>
    <p class="text-center">Here's how you can sell this subscription directly from your website</p>
    <div class="col-md-8 offset-md-2 p-3 mb-3 card" >
        <p>1) Add the following button below to your website. This will to route your customer to an Otruvez portal so that they can purchase the subscription to your service, {{$plan->stripe_plan_name}}</p>
        <div class="text-center p-3">
            <a style="background: #4cb996 !important; color: whitesmoke !important; display: inline-block; border-radius: .3em; width: 140px; height: 60px; padding: 5px; line-height: 3">Subscribe</a>
        </div>
        <pre id="button-code" style=" background-color: lightgray" class="p-2">
{{{ '<a'}}} style="background: #4cb996 !important; color: whitesmoke !important; display: inline-block; border-radius: .3em; width: 140px; height: 60px; padding: 5px; line-height: 3;" href="{{$portalLink}}">Subscribe {{{'</a>' }}}
        </pre>
        <h4 class="copy theme-color text-center" data-target-copy="#button-code"><span class="fa fa-copy float-right"></span></h4>
        <hr>
        <p class="text-center"><span class="theme-color">Note*</span> you may style this this button however you like. as long as the href attribute is the same:<br>
            <span class="theme-color">{{env('APP_URL')}}{{$portalLink}}</span>
        </p>
    </div>

    <div class="col-md-8 offset-md-2 p-3 mb-3 card" >
        <p>2) After your customer subscribes, they will need a place to go. If you already haven't, <span class="text-danger">please provide us with a url </span>for your customer to be redirected to after they've signed up</p>
        <div class="text-center p-3">
            <form id="redirect-to-form" action="/business/updateRedirectTo" method="post">
                {{csrf_field()}}
                <input class="form-control bg-white" value="{{$business->redirect_to ?: ''}}" placeholder="www.example.com/thanks" type="text" id="redirect-to-url" name="redirect_to">
                <button type="submit" class="btn theme-background m-3">Save redirect url</button>
            </form>
        </div>
        <hr>
    </div>
{{--PORTAL END--}}

{{--API CALLS--}}
    <hr>
    <div class="col-md-8 offset-md-2 p-3 mb-3">
        <h2 class="text-center">API calls</h2>
        <p class="text-center">Whether your customer has signed up through your site or through us, you may want to check their status, or allow them to unsubscribe. You will need your api key and a few requests that we'll show you below</p>
        <div class="text-center">
            <button class="btn theme-background" data-toggle="collapse" data-target="#showapikey">Show API Key</button>
        </div>
    </div>

    <div id="showapikey" class="collapse"><hr>
        <p class="text-center">{{$business->api_key}} </p>
        <h3 class="text-center"> <span class="fa fa-copy"></span> copy</h3>
    </div>

    <div class="col-md-8 offset-md-2 p-3 mb-3 card">
        <p>1) To get the status on a customer's account, you will need to do the following POST request: </p>
        <div class="col-md-12 p-3">
            <p class="">Route</p>
            <pre style=" background-color: lightgray" class="p-1">
https://otruvez.com/api/status
            </pre>
            <h4 class="copy theme-color text-center" data-target-copy="#button-code"><span class="fa fa-copy float-right"></span></h4>
        </div>

        <div class="col-md-12 p-3">
            <p class="">Method</p>
            <pre style=" background-color: lightgray" class="p-1">
POST
                </pre>
        </div>


        <div class="col-md-12 p-3">
            <p class="">Request</p>
            <pre style=" background-color: lightgray" class="p-3">
{
    'api_key': '{{$business->api_key}}',
    'business_id': '{{$business->id}}',
    'subscription_id: 'sub_id',
    'user_email: 'user_email'
}
            </pre>
            <h4 class="copy theme-color text-center" data-target-copy="#button-code"><span class="fa fa-copy float-right"></span></h4>
        </div>

        <div class="col-md-12 p-3">
            <p class="">Response</p>
            <pre style=" background-color: lightgray" class="p-3">
{
    'account_status': paid|unpaid|error,
    'http_status_code': [int],
    'service_name: '{{$plan->stripe_plan_name}}',
    'subscription_id: 'sub_id',
    'user_email: 'user_email',
    'error_message': [you will get this message if your request is malformed]
}
            </pre>
        </div>

        <div class="col-md-12 p-3">
            <p class="">If their account is <span class="text-danger">unpaid</span> , please send them to the following URL to fix the issue:</p>
            <p class="theme-color">https://otruvez.com/account/updatePayment</p>
            <pre style=" background-color: lightgray" class="p-3">
{{'<a target="_blank" href="https://otruvez.com/account/updatePayment">update payment</a>'}}
            </pre>
            <h4 class="copy theme-color text-center" data-target-copy="#button-code"><span class="fa fa-copy float-right"></span></h4>
        </div>
    </div>

    <div class="col-md-8 offset-md-2 p-3 mb-3 card">
        <p>2) Should the customer decide they no longer want the subscription, they can always access their account directly at <span class="theme-color">otruvez.com</span>. However, the following DELETE request will do the trick as well </p>
        <div class="col-md-12 p-3">
            <p class="">Route</p>
            <pre style=" background-color: lightgray" class="p-1">
https://otruvez.com/api/unsubscribe
            </pre>
            <h4 class="copy theme-color text-center" data-target-copy="#button-code"><span class="fa fa-copy float-right"></span></h4>
        </div>

        <div class="col-md-12 p-3">
            <p class="">Method</p>
            <pre style=" background-color: lightgray" class="p-1">
POST
            </pre>
        </div>


        <div class="col-md-12 p-3">
            <p class="">Request</p>
            <pre style=" background-color: lightgray" class="p-3">
{
    'api_key': '{{$business->api_key}}',
    'business_id': '{{$business->id}}',
    'subscription_id: 'sub_id',
    'user_email: 'user_email',
    'unsubscribe': 'true'
}
            </pre>
            <h4 class="copy theme-color text-center" data-target-copy="#button-code"><span class="fa fa-copy float-right"></span></h4>
        </div>

        <div class="col-md-12 p-3">
            <p class="">Response</p>
            <pre style=" background-color: lightgray" class="p-3">
{
    'account_status': 'deleted|error',
    'http_status_code': [int],
    'service_name: '{{$plan->stripe_plan_name}}',
    'confirmation_id: 'sub_id',
    'user_email: 'user_email',
    'error_message': [you will get this message if your request is malformed or if there was a problem on our side]
}
            </pre>
        </div>

    </div>
@endsection