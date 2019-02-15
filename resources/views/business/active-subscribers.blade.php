@extends('layouts.app')
@section('body')
    @include('partials.business-back')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h3 class="text-center">Active Subscribers</h3>
            <h6 class="text-center">Total subscription count: {{count($subscribers)}}</h6>
            <p><input class="form-control" placeholder="filter results with matching term" id="list-filter"></p>
            <ul>
                @forelse($subscribers as $user)
                    <li class="card-text p-2 filterable-containter">
                        <hr>
                        <p>
                            <span class="filterable">{{sprintf("%s %s", $user->first, $user->last)}}</span><br>
                            <span class="theme-color filterable">{{$user->email}}</span> <br>
                            Subscribed to: <span class="theme-color filterable">{{removeLastWord($user->name) }} @ {{formatPrice($user->price)}} / {{$user->o_interval}}</span>
                        </p>

                    </li>
                @empty
                    <li class="card-header">
                        <h4><b>No subscribers yet</b></h4>
                    </li>
                @endforelse
            </ul>

        </div>
    </div>

@endsection