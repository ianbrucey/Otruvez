@extends('layouts.app')

@section('body')
    <h3 class="text-center">Account Home</h3>
    <p class="text-center">{{$user->email}}</p>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                    <ul class="list-group text-center" style="border: none">
                        <a href="/account/mysubscriptions" class="list-group-item theme-background text-default">My Subscriptions</a>
                        <a href="/account/notifications" class="list-group-item {{ hasNewNotifications() ? 'red-background' : 'theme-background' }} text-default"> Notifications </a>
                        <a href="/account/support" class="list-group-item theme-background text-default"> Customer Support</a>
                        <a href="/account/updatePayment" class="list-group-item theme-background text-default"> Update Payment method</a>
                        <a href="/account/delete" class="list-group-item theme-background text-default"> Delete your Account</a>
                    </ul>
            </div>
        </div>
    </div>
@endsection
