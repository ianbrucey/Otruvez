@extends('layouts.app')
@section('body')
    @include('partials.base-theme.store-nav')
    <div class="container" style="margin-bottom: 1000px">
        <div class="row">
            <div class="col-md-8 offset-md-2">

                <div class="card mt-4">
                    <h4 class="card-header">About Us</h4>
                    <div class="card-body">
                        <p class="card-text">{{$business->description}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.navbar-brand').hide();
    </script>
@endsection