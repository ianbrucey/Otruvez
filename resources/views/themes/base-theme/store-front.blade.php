@extends('layouts.app')

@section('body')
  @php
    $showCarousel = false;
    $hasPlans     = false;
  @endphp

  @include('partials.base-theme.store-nav')

    <!-- Page Content -->
    <div class="container">

      <div class="row">


        <!-- /.col-lg-3 -->

        <div class="col-lg-12">
          {{--CAROUSEL WILL BE DISABLED FOR NOW--}}
          {{--<div id="carouselIndicators" class="carousel slide my-4" data-ride="carousel" style="display: none;">--}}
            {{--<ol class="carousel-indicators">--}}
              {{--<li data-target="#carouselIndicators" data-slide-to="0"></li>--}}
              {{--<li data-target="#carouselIndicators" data-slide-to="1"></li>--}}
              {{--<li data-target="#carouselIndicators" data-slide-to="2"></li>--}}
            {{--</ol>--}}
            {{--<div class="carousel-inner" role="listbox">--}}
              {{--@if($business->photo_path)--}}
                {{--@php $showCarousel = true; $photoActive = 1 @endphp--}}
                {{--<div class="carousel-item {{$photoActive ? 'active' : ''}}" style="height: 220px; max-height: 320px; background: url({{ $hasPhoto ? getImage($business->photo_path) : ''}}) no-repeat black; background-size: contain; background-position: center; ">--}}
                {{--</div>--}}
              {{--@endif--}}
              {{--@foreach($business->plans() as $plan)--}}
                  {{--@if($plan->featured_photo_path)--}}
                  {{--@php $showCarousel = true; @endphp--}}
                    {{--<div class="carousel-item {{!$photoActive ? 'active' : ''}}" style="height: 220px; max-height: 320px; background: url({{ getImage($plan->featured_photo_path) }}) no-repeat black; background-size: contain; background-position: center; ">--}}
                    {{--</div>--}}
                    {{--@php $showCarousel = true; $photoActive = 1 @endphp--}}
                  {{--@endif--}}
              {{--@endforeach--}}
            {{--</div>--}}
            {{--<a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">--}}
              {{--<span class="carousel-control-prev-icon" aria-hidden="true"></span>--}}
              {{--<span class="sr-only">Previous</span>--}}
            {{--</a>--}}
            {{--<a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">--}}
              {{--<span class="carousel-control-next-icon" aria-hidden="true"></span>--}}
              {{--<span class="sr-only">Next</span>--}}
            {{--</a>--}}
          {{--</div>--}}

          <div class="row">
            <div class="col-sm-12"><hr></div>

            @foreach($business->plans() as $plan)
              @if(!empty($plan->featured_photo_path))
                @php $hasPlans = true; @endphp
                <div class="col-sm-12 col-md-4">
                  <!-- product card -->
                  <div class="product-item bg-light">
                    <div class="card">
                      <div class="thumb-content" style="width: 100%; height: 200px; background: url({{getImage($plan->featured_photo_path)}}) no-repeat; background-size: contain; background-position: center">

                      </div>
                      <div class="card-body">
                        <h4 class="card-title"><a href="">{{html_entity_decode($plan->stripe_plan_name)}}</a></h4>
                        <ul class="list-inline product-meta">
                          <li class="list-inline-item">
                            <a href=""><i class="fa fa-briefcase"></i>{{$plan->business['name']}}</a>
                          </li>

                          <li class="list-item">
                            <a href=""><i class="fa fa-location-arrow"></i>{{$plan->business['city']}}, {{$plan->business['state']}}</a>
                          </li>
                        </ul>
                        <div class="product-ratings">
                          <ul class="list-inline">
                          <span class="text-warning">
                            {{getRatingStars($plan->ratings())}}
                          </span>
                            <a class=" list-inline-item float-right">{{formatPrice($plan->month_price)}} / month {{-- - {{formatPrice($plan->year_price)}} --}}</a>
                          </ul>

                        </div>
                      </div>
                      <div class="card-header">
                        <a href="/business/viewService/{{$plan->id}}" class="text-info">view service</a>
                      </div>
                    </div>
                  </div>
                </div>
              @endif
            @endforeach
            @if(!$hasPlans)
              <div class="col-sm-12">
                <h4 class="text-center theme-color">Services are coming soon!</h4>
              </div>
            @endif


          </div>
          <!-- /.row -->

        </div>
        <!-- /.col-lg-9 -->

      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->
  <script>
    @if($showCarousel)
      $('#carouselIndicators').show();
    @endif
  </script>
  <script>
//    $('.navbar-brand').children('img').hide();
  </script>
@endsection