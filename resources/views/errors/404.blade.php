@extends('layouts.app')
@section('body')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="">
                <h1 class="text-center"><img src="{{getOtruvezLogoImg()}}" width="200"></h1>
                <div class="card theme-background text-white-children text-center">
                    <h2 class="text-center">404</h2>
                    <h4 class="text-center ">Whoops! Looks like you've tried a URL that doesn't exist
                        <br> Please check your URL and try again.
                    </h4>
                    <h2></h2>
                    <div class="col-md-8 offset-md-2"><a class="btn bg-white theme-color" href="{{back()}}">Go Back</a></div>
                </div>
            </div>
        </div>
    </div>
@endsection