@extends('layouts.app')

@section('body')
    @include('partials.account-back')
    <h3 class="text-center"> My Subscriptions</h3>
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if($mustUpdatePaymentMethod)
                    <div class="alert alert-danger">
                        Your subscriptions are suspended. Please update your payment method.
                        If you do not update your payment method soon, your subscriptions will be cancelled<br>
                        <a class="btn btn-danger btn-sm text-white m-auto" href="/account/updatePayment">
                            Update Payment Method
                        </a>
                    </div>


                @endif
            </div>
            <div class="col-md-8 offset-md-2">

                @forelse($subscriptions as $subscription)
                    @php $plan = $subscription->plan(); @endphp
                <div class="card">
                    {{--@if($mustUpdatePaymentMethod || true)--}}
                        {{--<div class="card" style="width: 100%; height: 100%; position: absolute; left: 0; top: 0px; background: rgba(0,0,0,.5)"></div>--}}
                    {{--@endif--}}
                        <div class="card-header">
                            {{removeLastWord($subscription->name)}}
                            {{--- {{$subscription->uses ? : 0}}/{{$plan->use_limit}} uses--}}
                            <form method="POST" action=/subscription/cancel/{{$subscription->id}}" style="display: inline-block" class="float-right">
                                {{csrf_field()}}
                                {{method_field("DELETE")}}
                                <input type="hidden" name="is_business_account" value="0">
                            </form>
                        </div>
                        <div class="card-body">
                            <p>{{$plan->description}}</p>
                            <img src="{{getImage($plan->featured_photo_path)}}" width="200">
                            <hr>
                            <button class=" col-sm-12 btn-sm theme-background show-sm-modal checkin" data-subscription-id="{{$subscription->id}}" data-plan-id="{{$plan->id}}" data-modal-target="#checkin-{{$subscription->id}}" {{$mustUpdatePaymentMethod ? "disabled" : ""}}><span class="fa fa-check-circle"></span> Check-in </button> {{-- still needs to be worked out --}}
                            <button class=" col-sm-12 btn-sm theme-background" {{$mustUpdatePaymentMethod ? "disabled" : ""}}><span class="fa fa-eye"></span> View Details </button> {{-- we need a modal for this --}}
                            <button class=" col-sm-12 btn-sm theme-background" href="{{$mustUpdatePaymentMethod ? "#" : '/business/viewService/'.$plan->id.'/#review-container'}}" ><span class="fa fa-pencil-square"></span> Write A Review </button>
                            <button class=" col-sm-12 btn-sm theme-background show-sm-modal" data-modal-target="#rate-{{$plan->id}}" {{$mustUpdatePaymentMethod ? "disabled" : ""}}><span class="fa fa-star"></span> Rate </button>
                            <hr>
                            <button type="submit" class="btn-sm btn-danger "> Cancel Subscription </button> {{-- still needs to be worked out --}}
                            <hr>
                        </div>
                </div>
                @include('modals.custom.checkin-modal')
                @include('modals.custom.ratings-modal')
                @empty
                    <div class="card">
                        <div class="card-header">No subscriptions yet!</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="{{baseUrlConcat('/js/ajax/checkin.js')}}"></script>
@endsection
