@extends('layouts.app')

@section('body')


<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2" href="#">
            <h3 class="text-center">Welcome, {{"@".$data['business_handle']}} </h3>
            <div class="row theme-background p-2" id="scoreboard">
                <div class="col-6  ">
                    <h4 class="text-center text-default">Active Subscriptions </h4>
                    <p class="text-center text-default"><span>{{$data['subscriptionCount']}}</span></p>
                </div>
                <div class="col-6">
                    <h4 class="text-center text-default">Projected monthly income </h4>
                    <p class="text-center text-default"><span>{{$data['projectedMonthlyIncome']}}</span></p>
                </div>
            </div>


                <h3></h3>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row b-home-cards">
        <hr>
        <div class="col-md-12">
            <hr>
            <p class="text-center">Provide customers with this direct link to your store:<br>
                <span class="theme-color">{{env('APP_URL')}}/store/{{$data['business_handle']}}</span>
            </p>
        </div>
        <a class="col-md-4" href="/business/manageBusiness">
            <div class="card">
                <span class="fa fa-briefcase fa-2x"></span>
                <h3>Manage Business</h3>
            </div>
        </a>

        <a class="col-md-4" href="/plan/managePlans">
            <div class="card">
                <span class="fa fa-shopping-cart fa-2x"></span>
                <h3>Manage Services</h3>
            </div>
        </a>


        <a class="col-md-4" href="/business/viewStore/{{$data['business_handle']}}">
            <div class="card">
                <span class="fa fa-eye fa-2x"></span>
                <h3>Preview Store</h3>
            </div>
        </a>

        <a class="col-md-4" href="/business/notifications">
            <div class="card">
                <span class="fa fa-bell fa-2x"></span>
                <h3>Notifications</h3>
            </div>
        </a>

        <a class="col-md-4" href="/business/checkins">
            <div class="card">
                <span class="fa fa-envelope fa-2x"></span>
                <h3>Check-ins</h3>
            </div>
        </a>

        <a class="col-md-4" href="/business/subscribers">
            <div class="card">
                <span class="fa fa-user fa-2x"></span>
                <h3>Active Subscribers</h3>
            </div>
        </a>

        <a class="col-md-4" href="/business/notifyCustomers">
            <div class="card">
                <span class="fa fa-bullhorn fa-2x"></span>
                <h3>Mass message to customers</h3>
            </div>
        </a>

        <a class="col-md-4" href="/business/payoutOptions">
            <div class="card">
                <span class="fa fa-dollar fa-2x"></span>
                <h3>Payout Options</h3>
            </div>
        </a>

{{----------------------------------------------------------CANCEL----------------------------------------------------------------}}
        <a class="col-md-4" href="/business/cancel">
            <div class="card">
                <span class="fa fa-window-close fa-2x text-danger"></span>
                <h3>Cancel account</h3>
            </div>
        </a>
    </div>
</div>
@endsection
