@extends('layouts.app')
@section('body')
    @include('partials.base-theme.store-nav')
    <div class="container" style="margin-bottom: 1000px">
        <div class="row">
            <div class="col-md-8 offset-md-2">

                <div class="card mt-4">
                    <h4 class="card-header">Contact info</h4>
                    <div class="card-body text-center">
                        <p>Phone: {{ $business->phone }}</p>
                        <div class="card-text mb-2"><a class="btn theme-background col-12" href="tel:{{$business->phone}}"><span class="fa fa-2x fa-phone"></span> <br> Call</a><hr></div>
                        <p>Email: {{ $business->email }}</p>
                        <div class="card-text mb-2"><a class="btn theme-background col-12" href="mailto:{{$business->email}}"><span class="fa fa-2x fa-envelope"></span><br> Send email</a><hr></div>
                        <p>Address: {{ $business->address }}</p>
                        <a class=" card-text btn theme-background col-12 mb-2" style="overflow: hidden" href="https://maps.google.com/?q={{$business->address}}">
                                <span class="fa fa-2x fa-map-marker"></span><br>
                            Get Directions
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection