@extends('layouts.app')
@section('body')
    @include('partials.account-back')
    @if($hasNewNotifications)
        <p class="alert alert-info">New Notifications</p>
    @endif
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h3 class="text-center">My Account Notifications</h3>
            @forelse($notifications as $notification)
                <div class="card">
                    <div class="card-footer">
                        <p style="font-size: 12px">
                        <b class="theme-color">{{$notification->sender_name}}</b>
                            <span class="float-right">{{formatDate($notification->created_at, "m-d-Y")}}</span>

                        <hr>
                        </p>
                        <h6><b>{{$notification->subject}}</b></h6>
                        <button class="theme-background float-left round-5" data-toggle="collapse" data-target="#an-{{$notification->id}}">show</button>
                    </div>
                    <div class="card-body collapse" id="an-{{$notification->id}}">
                        @if($notification->is_template == "1")
                            @php /** @var \App\Notification $notification */ @endphp
                                {!! $notification->renderNotificationView($notification->type) !!}
                        @else
                            {!! $notification->body !!}
                        @endif
                    </div>
                </div><br>
            @empty
                <div class="card">
                    <div class="card-header">
                        <h4><b>No notifications yet</b></h4>
                    </div>
                </div><br>
            @endforelse

        </div>
    </div>

@endsection