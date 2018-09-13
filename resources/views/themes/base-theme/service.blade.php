@extends('layouts.app')
@section('body')

@php
$intervals = ['month','year'];
@endphp
    <!-- Page Content -->
@include('partials.base-theme.store-nav')
    <div class="container">

        <div class="row">


            <!-- /.col-lg-3 -->

            <div class="col-lg-9">

                <div class="card mt-4 no-shadow">
                    <h1 class="product-title">{{$plan->stripe_plan_name}}</h1>
                    <div class="product-meta">
                        <ul class="list-inline">
                            <li class="list-inline-item"><i class="fa fa-user-o"></i> By <a href="">{{$business->name}}</a></li>
                            <li class="list-inline-item"><i class="fa fa-location-arrow"></i> <a href="">{{$business->city}}, {{$business->state}}</a></li>
                        </ul>
                    </div>
                    @if($plan->featured_photo_path)
                        <div class="hd-img-top img-fluid" style="background: url({{getImage($plan->featured_photo_path)}}) no-repeat black; height: 320px; max-height: 320px; background-size: contain; background-position: center; "> </div>
                    @else
                        <hr>
                    @endif
                    @if(count($plan->photos))
                        <h5 class="card-body btn theme-background text-default" data-toggle="collapse" href="#photoGallery">
                            <span class="fa fa-photo"></span> View more photos
                        </h5>
                    @endif
                    <div class="card-body row collapse" id="photoGallery">
                        {{--<h6 class="col-md-12 text-info text-center">click to enlarge</h6>--}}
                        @foreach($plan->photos as $photo)

                            <div style="background: url({{getImage($photo->path)}}) no-repeat transparent; width: 32%; height: 100px; background-size: contain; background-position: center; display: inline-block; border: 1px solid lightgray" href="{{getImage($photo->path)}}" data-lity>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-body">
                        <h4>Description</h4>
                        <p class="card-text">{{$plan->description}}</p>
                        <hr>
                        <h5>Usage Limit:</h5>
                        <p class="card-text theme-color"><strong>{{getUseLimitString($plan)}}</strong></p>

                    </div>
                </div>
                <!-- /.card -->

                <div class="card card-outline-secondary my-4 no-shadow">
                    <div class="card-header">
                        Service Reviews

                        <div class="float-right">
                            <span class="text-warning">
                                {{getRatingStars($rating)}}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="review-container">
                            @forelse($reviews as $review)
                                <div class="review">
                                    <p>{{$review->body}}</p>
                                    <small class="text-muted">Posted by <b>{{$review->name}}</b> on <span><b>{{formatDate($review->created_at,'M-d-Y')}}</b></span></small>
                                    @if($review->user_id === \Illuminate\Support\Facades\Auth::id())
                                        <form action="/review/deleteReview/{{$review->id}}" method="post" class="float-right">
                                            <button type="submit" class="text-danger">delete</button>
                                            {{method_field('delete')}}
                                            {{csrf_field()}}
                                        </form>
                                    @endif
                                </div>
                            @empty
                                <h3>No reviews yet</h3>
                            @endforelse
                        </div>
                        <hr>
                        @if(!$hasReview && !$owner)
                            <form method="post" action="/review/addReview/{{$business->id}}" class="form-group validate-review" id="review-form">
                                <textarea name="body" placeholder="write your review here" class="form-control-lg review-body" id="review-body" required></textarea><br>
                                {{csrf_field()}}
                                <br>
                                <button type="submit" class="btn btn-success" id="service-review-button">Leave a Review</button>
                            </form>
                        @endif
                    </div>
                </div>
                <!-- /.card -->

            </div>

            <div class="col-lg-3">
                <div class="card my-4 no-shadow">
                    <div class="card-header theme-background text-default">
                        Subscribe
                    </div>
                    <div class="list-group">
                            @if($alreadySubscribed)
                                <div class="list-group-item text-center ">
                                    <a href="/account/mysubscriptions#subscription-details-{{$plan->id}}" class="btn-sm theme-background"><span class="fa fa-star"></span> Use this subscription </a>
                                </div>
                            @elseif($owner)
                            <div class="list-group-item text-center ">
                                <p class=""> You own this subscription </p>
                            </div>
                            @else
                                @foreach($intervals as $interval)

                                    <form action="/subscription/subscribe" class=" list-group-item text-center stripe-payment-form" method="POST" >
                                        <h5 class="">{{$interval == 'month' ? formatPrice($plan->month_price) . " / month" : formatPrice($plan->year_price) . " / year"}}</h5>
                                        <script
                                                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                data-key="{{$publicStripeKey}}"
                                                data-amount="{{$interval == 'month' ? $plan->month_price : $plan->year_price}}"
                                                data-name="{{$plan->stripe_plan_name}} {{strtoupper($interval)}}"
                                                data-description="For plan: {{$plan->stripe_plan_id}}_{{$interval}}"
                                                data-image="{{ $haslogo ? getImage($business->logo_path) : ''}}"
                                                data-locale="auto">
                                        </script>
                                        <input type="hidden" name="plan_id" value="{{$plan->id}}">
                                        <input type="hidden" name="stripe_plan_id" value="{{$plan->stripe_plan_id}}_{{$interval}}">
                                        <input type="hidden" name="stripe_plan_name" value="{{$plan->stripe_plan_name}} {{strtoupper($interval)}}">
                                        <input type="hidden" name="is_app_plan" value="{{$plan->is_app_plan}}">
                                        <input type="hidden" name="business_id" value="{{$plan->business_id}}">
                                        <input type="hidden" name="price" value="{{$interval == 'month' ? $plan->month_price : $plan->year_price}}">
                                        <input type="hidden" name="o_interval" value="{{$interval}}">
                                        {{csrf_field()}}
                                        <input type="hidden" name="user_id" value="{{\Illuminate\Support\Facades\Auth::id()}}">
                                    </form>
                                @endforeach
                            @endif
                    </div>
                </div>
            </div>

            <!-- /.col-lg-9 -->

        </div>

    </div>
    <script>
        $( window ).unload(function() {
            // $('#blanket').show();
            $('#blanket').fadeIn(250);
        });
    </script>
 @endsection
