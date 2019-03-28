@extends('layouts.app')

@section('body')
<div class="container-fluid">
    @include('partials.home.search-module')
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="search-result bg-gray">
                <h2 class="theme-color text-center">Search for local deals in your area</h2>
            </div>
        </div>

        <br>
        {{--@for($i = 0; $i < 12; $i++)--}}
        {{----}}
        {{--@endfor--}}
    </div>
</div>

@endsection

@section('footer')
    <script src="{{baseUrlConcat('/js/google-location/set-address.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgE-TwAg_-s8NzBqtdJtkMVlQ86Qf9nho&libraries=places&callback=initAutocompleteCities"
            async defer></script>
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
