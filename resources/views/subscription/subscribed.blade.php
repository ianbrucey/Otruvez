@extends('layouts.app')

@section('body')
    @if(!session('planName'))
        <div class="container">
            <div class="row">
                <div class="col-md-4 offset-md-4 text-center">
                    <h1>Why are you here?</h1>
                    <h2 class="btn btn-primary">
                        Dave Chappelle.....GO HOME!!!
                    </h2>
                </div>
            </div>
        </div>
    @else
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card ">
                    <p class="text-center p-20">Success!</p>
                    <p class="text-center p-20" >Thank you for subscribing to <br>
                        <span class="theme-color p-26">{{removeLastWord(session('planName'))}}</span>
                        <hr>
                    </p>

                    <p class="text-center p-20" >Details <br>
                    <span class="text-center theme-color p-24">{{formatPrice(session('price'))}} / {{session('interval')}}</span>
                    </p>

                    <hr>
                    <h3 class="text-center"><a class="btn theme-background col-12" href="/account/mysubscriptions">Go to my active subscriptions</a></h3>
                    <h3 class="text-center"><a href="/home" class="btn bg-white theme-color col-12 ">Keep shopping</a></h3>
                </div>
            </div>
        </div>
    </div>
    @endif

@endsection
