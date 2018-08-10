@extends('layouts.app')

@section('body')
<h3 class="text-center">Welcome {{$data['name']}}! Check your stats below</h3>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2" href="#">
            <div class="row theme-background p-2" id="scoreboard">
                <div class="col-6 text-default ">
                    <p class="text-default">Subscriptions </p>
                    <p class="text-default">Projected monthly income </p>
                </div>
                <div class="col-6">
                    <p class="text-default"><span>{{$data['subscriptionCount']}}</span></p>
                    <p class="text-default"><span>{{$data['projectedMonthlyIncome']}}</span></p>
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
            <h4 class="text-center"><u>Manage your account</u></h4>
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

        <a class="col-md-4" href="/business/viewStore/{{$data['businessId']}}">
            <div class="card">
                <span class="fa fa-eye fa-2x"></span>
                <h3>Preview Store</h3>
            </div>
        </a>

        <a class="col-md-4" href="/business/notifications/{{$data['businessId']}}">
            <div class="card">
                <span class="fa fa-bell fa-2x"></span>
                <h3>Notifications</h3>
            </div>
        </a>

        <a class="col-md-4" href="/business/checkins/{{$data['businessId']}}">
            <div class="card">
                <span class="fa fa-envelope fa-2x"></span>
                <h3>Check-ins</h3>
            </div>
        </a>
        <a class="col-md-4" href="/business/notifyCustomers">
            <div class="card">
                <span class="fa fa-bullhorn fa-2x"></span>
                <h3>Mass message to customers</h3>
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
