@extends('layouts.app')

@section('body')
    <div class="container-fluid" id="search-container">
        @include('partials.home.search-module')
    </div>

    <div class="container">
        <div class="row">
            @if(count($plans) || $queryString)
                <div class="col-md-12">
                    <div class="search-result bg-gray">
                        {{--using default distance here--}}
                        @if(!empty($searchField))
                            <h4>Results For "{{$queryString}}"  </h4>
                            <p class="text-info">{{$category > 0 ? sprintf('category: %s', SERVICE_CATEGORY_LIST[$category])  : ""}}</p>
                            <p>{{$totalResultCount}} {{$totalResultCount == 1 ? 'result' : 'results'}} within {{$miles}} miles of {{$location->city}}, {{$location->state}}</p>
                            @if($searchFrom)
                                <p class="theme-color small">Showing {{$searchFrom+1}} - {{$searchFrom+25}}</p>
                            @else
                                <p class="theme-color small">Showing 1 - {{$maxResults}}</p>
                            @endif
                        @else
                            <p>Local services in the {{$location->city}}, {{$location->state}} area</p>
                        @endif

                    </div>
                </div>
            @else
                <div class="col-md-12">
                    <div class="search-result bg-gray">
                        <h2>Local deals in your area</h2>
                    </div>
                </div>
            @endif

            @if($totalResultCount >= $maxResults && !empty($searchField))
                {{--PAGINATION--}}
                <div class="col-md-12">
                    @if($leftArrow)
                        <a href="#" data-from="{{$rightArrowFrom}}"><span class="fa fa-arrow-left"></span></a>
                    @endif
                    @for($i = $loopStart; $i <= $loopEnd; $i++)
                        <a href="#" class="{{ !$searchFrom && $i == 1 || $searchFrom == ($i - 1) * $maxResults ? 'theme-color' : ''}}" data-from="{{$i == 1 ? $i - 1 : ($i - 1) * $maxResults}}" onclick="triggerTargetSubmit(event, this, false)" data-target="#search-form">| {{$i}}</a>

                    @endfor
                    @if($rightArrow)
                        <a href="#" data-from="{{$rightArrowFrom}}"><span class="fa fa-arrow-right"></span></a>
                    @endif

                    {{--@for($i = $pages > 10 ? $pages - 10 : 1; $i <= $pages + 1; $i++)--}}
                    {{--@if($pages < $i)--}}
                    {{--<a href="#" data-from="{{$i}}" onclick="triggerTargetSubmit(event, this, false)" data-target="#search-form"><span class="fa fa-arrow-right"></span> </a>--}}
                    {{--@else--}}
                    {{--<a href="#" class="{{$searchFrom == $i ? 'text-info' : ''}}" data-from="{{$i}}" onclick="triggerTargetSubmit(event, this, false)" data-target="#search-form">{{$i}} | </a>--}}
                    {{--@endif--}}
                    {{--@endfor--}}
                </div>
            @endif

            @forelse($plans as $plan)
                <div class="col-sm-12 col-md-4">
                    <!-- product card -->
                    <div class="product-item bg-light">
                        <div class="card">
                            <div class="thumb-content" style="width: 100%; height: 200px; background: url({{$plan->featured_photo_path ? getImage($plan->featured_photo_path) : ''}}) no-repeat; background-size: contain; background-position: center">

                            </div>
                            <div class="card-body">
                                <h4 class="card-title search-card-title">{{ truncateCardTitle($plan->stripe_plan_name) }}</h4>
                                <ul class="list-inline product-meta">
                                    <li class="list-inline-item">
                                        <a href=""><i class="fa fa-briefcase"></i><span class="theme-color">{{"@".$plan->business['business_handle']}}</span></a>
                                    </li>

                                    <li class="list-item">
                                        <a href=""><i class="fa fa-location-arrow"></i>{{  (empty($plan->business['city']) && empty($plan->business['state'])) ? "Online Business" : sprintf("%s, %s - %s mi",$plan->business['city'],$plan->business['state'],howFarAway($location->latitude,$location->longitude,$plan->business['lat'],$plan->business['lng']) ) }}</a>
                                    </li>
                                </ul>
                                <div class="product-ratings">
                                    <ul class="list-inline">
                                        <span class="text-warning">
                                            {{getRatingStars($plan->rating)}}
                                        </span>
                                        <a class=" list-inline-item float-right">{{formatPrice($plan->month_price)}} - {{formatPrice($plan->year_price)}}</a>
                                    </ul>

                                </div>
                            </div>
                            <div class="card-header">
                                <a href="/business/viewService/{{$plan->id}}" class="text-info">view service</a>
                                <a href="/business/viewStore/{{$plan->business['business_handle']}}" class="float-right text-info">Go to store</a>
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <br>
                <div class="col-sm-12 offset-md-4 col-md-4">
                    <!-- product card -->
                    <div class="product-item bg-light">
                        <div class="card">
                            <div class="card-header text-center">
                                <h3>No services available in your area.</h3>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

@endsection

@section('footer')
    <script src="{{baseUrlConcat('/js/google-location/set-address.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgE-TwAg_-s8NzBqtdJtkMVlQ86Qf9nho&libraries=places&callback=initAutocompleteCities"
            async defer></script>
    <script src="{{baseUrlConcat('/js/index.js')}}"></script>
    <script>
        $(window).keydown(function(event){
            if(event.keyCode === 13) {
                if($('#autocomplete').is(":focus")) {
                    event.preventDefault();
                    return false;
                }
            }
        });
    </script>

@endsection
