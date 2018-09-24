@extends('layouts.app')
@section('body')
    @include('partials.account-back')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            @if(!$hasValidPaymentMethod)
                <div class="alert alert-danger">
                    Your subscriptions are suspended. Please update your payment method
                </div>

            @endIf
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Payment Method</h4>
                    @include('errors.request-errors')
                </div>
                @if(!empty($user->card_last_four))
                    <ul class="m-2">
                        <li><u><b>Current card details</b></u></li>
                        <li>Card Type: {{$user->card_brand}}</li>
                        <li>CC Number: ****{{$user->card_last_four}}</li>
                    </ul>
                @else
                    <ul>
                        <li><h3><b>*No card on file*</b></h3></li>
                    </ul>
                @endif
                <form action="/account/updatePaymentMethod" method="POST" class="mt-4 text-center">
                    <script
                            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-key="pk_test_GQCUjPm3eovgzCUVD2RmzTjU"
                            data-image="{{getOtruvezLogoImg()}}"
                            data-name="Your Website Name"
                            data-panel-label="Update Card Details"
                            data-label="Update Card Details"
                            data-allow-remember-me=false
                            data-locale="auto"
                            {{--data-email="{{$user->email}}"--}}
                    >

                    </script>
                    {{csrf_field()}}
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $(this).find('.stripe-button').css('background','theme-background');
        });
    </script>
@endsection