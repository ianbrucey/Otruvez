@extends('layouts.app')
@section('body')
    @include('partials.business-back')
    {{--@if($hasNewNotifications)--}}
        {{--<p class="alert alert-info">New Notifications</p>--}}
    {{--@endif--}}
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h3 class="text-center">Business Notifications</h3>
            @forelse($notifications as $notification)
                <div class="card">
                    <div class="card-footer">
                        <p>
                            From: <b>{{$notification->sender_name}}</b>
                            <span class="float-right">{{formatDate($notification->created_at, "m-d-Y")}}</span>
                            <hr>
                        </p>
                        <h4><b>{{$notification->subject}}</b></h4>
                        <button class="theme-background float-left round-5" data-toggle="collapse" data-target="#bn-{{$notification->id}}">show</button>
                    </div>
                    <div class="card-body collapse" id="bn-{{$notification->id}}">
                        @if($notification->is_template)
                            @php /** @var \App\Notification $notification */ @endphp
                            {!! $notification->renderNotificationView($notification->type) !!}
                        @else
                            {!!$notification->body!!}
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