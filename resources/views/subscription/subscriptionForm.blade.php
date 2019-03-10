<?php
$plan            = $data['planToSubscribe'];
$publicStripeKey = $data['publicStripeKey'];
$price           = $data['price'];
$interval        = $data['interval'];
?>

@extends('layouts.app')

@section('body')
    <style>
    </style>
    <div class="container-fluid">
        <a class="btn btn-info" href="{{ URL::previous() }}">Back</a>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h1 class="text-center">Subscribe to {{html_entity_decode($plan->stripe_plan_name)}}</h1>
                        <h3 class="text-center">at {{formatPrice($price)}} a {{$interval}}</h3>
                    </div>
                    <div class="card-body">
                        <form action="/subscription/subscribe" class="text-center" method="POST">
                            <script
                                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                    data-key="{{$publicStripeKey}}"
                                    data-amount="{{$price}}"
                                    data-name="{{html_entity_decode($plan->stripe_plan_name)}} {{strtoupper($interval)}}"
                                    data-description="For plan: {{$plan->stripe_plan_id}}_{{$interval}}"
                                    data-image="/logo.png"
                                    data-locale="auto">
                            </script>
                            <input type="hidden" name="stripe_plan_id" value="{{$plan->stripe_plan_id}}_{{$interval}}">
                            <input type="hidden" name="stripe_plan_name" value="{{html_entity_decode($plan->stripe_plan_name)}} {{strtoupper($interval)}}">
                            <input type="hidden" name="is_app_plan" value="{{$plan->is_app_plan}}">
                            <input type="hidden" name="business_id" value="{{$plan->business_id}}">
                            <input type="hidden" name="price" value="{{$price}}">
                            <input type="hidden" name="o_interval" value="{{$interval}}">
                            {{csrf_field()}}
                            <input type="hidden" name="user_id" value="{{\Illuminate\Support\Facades\Auth::id()}}">
                        </form>
                    </div>
                </div>
            </div>
        </div>

@endsection
