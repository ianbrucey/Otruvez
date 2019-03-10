@extends('layouts.app')
@section('body')
    @include('partials.business-back')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h3 class="text-center">Active Subscribers</h3>
            <h6 class="text-center">Total subscription count: {{count($subscribers)}}</h6>
            <p><input class="form-control" placeholder="filter results by name, email or subscription name" id="list-filter"></p>
            <ul>
                @forelse($subscribers as $subscriber)
                    <li class="card-text p-2 filterable-containter">
                        <hr>
                        <div class="col-12">
                            <p>
                                <span class="filterable">{{sprintf("%s %s", $subscriber->first, $subscriber->last)}}</span><br>
                                <span class="theme-color filterable">{{$subscriber->email}}</span> <br>
                                Subscribed to: <span class="theme-color filterable">{{removeLastWord($subscriber->name) }} @ {{formatPrice($subscriber->price)}} / {{$subscriber->o_interval}}</span>
                            </p>
                        </div>
                        <div class="col-12">
                            <form method="POST" action="/subscription/cancel/{{$subscriber->id}}"  id="delete-subscription-form-{{$subscriber->id}}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="button" data-target="#delete-subscription-form-{{$subscriber->id}}" onclick="cancelSubscription(event, this)" class=" btn-danger p-1" style="border-radius: .4em">Cancel and Refund</button>
                            </form>
                        </div>

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