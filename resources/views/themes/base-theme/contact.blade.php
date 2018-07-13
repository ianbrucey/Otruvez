@extends('layouts.app')
@section('body')
    @include('partials.base-theme.store-nav')
    <div class="container" style="margin-bottom: 1000px">
        <div class="row">
            <div class="col-md-8 offset-md-2">

                <div class="card mt-4">
                    <h4 class="card-header">Contact info</h4>
                    <div class="card-body">
                        <h5 class="card-text">Phone: <a href="tel:{{$business->phone}}">{{$business->phone}}</a></h5>
                        <h5 class="card-text">Email: <a href="mailto:{{$business->email}}">{{$business->email}}</a></h5>
                        <h5 class="card-text">Address: <a href="https://maps.google.com/?q={{$business->address}}">{{$business->address}}</a></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection