@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.2.0/dropzone.css">
@endsection

@section('body')
    @if(!count($businesses))
        <h2 class="text-center">Merchant center</h2>
        <h4 class="text-center">To start selling subscriptions, enter your business's details</h4>

        @include('modals.custom.create-business-form')
        <hr>
    @else
    @include('partials.business-back')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12" href="#">
                <h3 class="text-center">Active Businesses</h3>
            </div>
            @if(count($businesses))
                @foreach($businesses as $business)
                    @php
                        $hasPhoto   = !empty($business->photo_path);
                        $haslogo    = !empty($business->logo_path);
                    @endphp
                    <div class="col-md-10 offset-md-1 card-body row" href="#">

                        <div class="col-md-6">
                            {{--PRIMARY BUSINESS LOGO--}}
                            <p class="text-center">
                                <a class="text-primary btn theme-background" id="update-business-logo" data-target="#business-logo-dropzone" onclick="triggerTargetClick(event, this)">update logo</a>
                            <form action="/business/updateLogo/{{$business->id}}" class="dropzone hide" id="business-logo-dropzone">
                                {{ csrf_field() }}
                                {{ form_method_field("POST") }}
                                <div class="fallback">
                                    <input name="file" type="file" multiple />
                                </div>
                            </form>
                            </p>
                            <div href="{{ $haslogo ? getImage($business->logo_path) : ''}}" class="business-logo-placeholder text-center" style="background-image: url({{$haslogo ? getImage($business->logo_path) : ''}})" {{$haslogo ? 'data-lity' : ''}}>
                                @if(!$haslogo) <span class="fa fa-photo fa-2x" style="display: block; margin-top: 30%"></span> @endif
                            </div>
                            @if($haslogo)
                                <p class="text-center">
                                    <a class="text-danger" id="delete-business-logo" data-target="#delete-business-logo-form" onclick="triggerTargetSubmit(event, this)">remove</a>
                                <form method="post" action="/business/deleteLogo/{{$business->id}}" class="hide" id="delete-business-logo-form">
                                    {{ csrf_field() }}
                                    {{ form_method_field("DELETE") }}
                                </form>
                                </p>
                            @endif
                            {{--PRIMARY BUSINESS LOGO--}}
                        </div>
                        <div class="col-md-6">
                            {{--PRIMARY BUSINESS PHOTO--}}
                            <p class="text-center">
                                <a class="text-primary btn theme-background" id="update-business_photo" data-target="#business-dropzone" onclick="triggerTargetClick(event, this)">update main photo</a>
                                <form action="/business/updatePhoto/{{$business->id}}" class="dropzone hide" id="business-dropzone">
                                    {{ csrf_field() }}
                                    {{ form_method_field("POST") }}
                                    <div class="fallback">
                                        <input name="file" type="file" multiple />
                                    </div>
                                </form>
                            </p>
                            <div href="{{ $hasPhoto ? getImage($business->photo_path) : ''}}" class="business-img-placeholder text-center" style="background-image: url({{$hasPhoto ? getImage($business->photo_path) : ''}})" {{$hasPhoto ? 'data-lity' : ''}}>
                                @if(!$hasPhoto) <span class="fa fa-photo fa-2x" style="display: block; margin-top: 30%"></span> @endif
                            </div>
                            @if($hasPhoto)
                            <p class="text-center">
                                <a class="text-danger" id="delete-business-photo" data-target="#delete-business-photo-form" onclick="triggerTargetSubmit(event, this)">remove</a>
                                <form method="post" action="/business/deletePhoto/{{$business->id}}" class="hide" id="delete-business-photo-form">
                                    {{ csrf_field() }}
                                    {{ form_method_field("DELETE") }}
                                </form>
                            </p>
                            @endif
                        {{--PRIMARY BUSINESS PHOTO--}}
                        </div>

                        <div class="card-body">
                            <h3 class="text-justify">{{$business->name}}</h3>
                            <p><i>"{{$business->description}}"</i></p>
                            <p>{{$business->email}}</p>
                            <p>{{$business->phone}}</p>
                            <p>{{$business->address}}</p>
                            <h5><b><u>Business hours</u></b></h5>
                            <div class="business-hours" style="display: block">
                                @foreach($days as $day)
                                    <div class="edit-label-div">
                                        <label>{{ucfirst($day)}}</label>
                                    </div>
                                    <div class="edit-input-div">
                                        <p>{{$business->$day}}</p>
                                    </div>

                                @endforeach
                            </div>
                        </div>

                        <div class="card-footer " style="width: 100%">
                            <button type="button" class="btn theme-background float-left" data-toggle="modal" data-target="#business-details-{{$business->id}}">Edit Details</button>
                            <a href="/plan/managePlans" class="btn theme-background float-right">manage services</a>
                        </div>

                            <!-- EDIT BUSINESS DETAILS Modal -->
                            @include('modals.bootstrap.edit-business-modal')

                    </div>
                @endforeach
            @else
                <h2 class="text-center text-white">
                    No active businesses. Click the button above to create one.
                </h2>
            @endif
        </div>
    </div>


    @endif
@endsection

@section('footer')


    <script src="{{ baseUrlConcat('/js/index.js') }}"></script>
    <script src="{{ baseUrlConcat('/js/dropzone.js') }}"></script>
    <script src="{{ baseUrlConcat('/js/dropzone-options.js') }}"></script>
    <script src="{{baseUrlConcat('/js/google-location/set-address.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuTqYHpeNjvxPvYQZG7JueMS9tClD7yVY&libraries=places&callback=initAutocomplete" async defer></script>


@endsection