@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.2.0/dropzone.css">
@endsection

@section('body')
    @if($business == null)
        <h2 class="text-center">Merchant center</h2>
        <h4 class="text-center">To start selling subscriptions, enter your business's details</h4>

        @include('modals.custom.create-business-form')
        <hr>
    @else
    @include('partials.business-back')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12" href="#">
                <h3 class="text-center">Your Business Details</h3>
                @include("errors.request-errors")
            </div>

                    @php
                        $hasPhoto   = !empty($business->photo_path);
                        $haslogo    = !empty($business->logo_path);
                    @endphp
                    <div class="col-md-10 offset-md-1 card-body row" href="#">
                        {{--<div class="col-12">--}}
                            {{--<hr>--}}
                            {{--<h4 class="text-center">Choose your store's theme color</h4>--}}

                            {{--<div class="col-4 offset-4">--}}
                                {{--<div class="alert alert-success m-4 hide" id="color-saved">--}}
                                    {{--Theme color saved--}}
                                {{--</div>--}}
                                {{--<form id="theme-color-form" action="/business/update/{{$business->id}}" method="post">--}}
                                    {{--<div class="text-center">--}}
                                        {{--<input type="color" class="form-control" id="theme-color" name="theme_color">--}}
                                        {{--<input type="hidden" name="async" value=1>--}}
                                        {{--{{csrf_field()}}--}}
                                        {{--{{method_field("put")}}--}}
                                    {{--</div>--}}
                                {{--</form>--}}
                            {{--</div>--}}
                            {{--<hr>--}}
                        {{--</div>--}}
                        {{--<script>--}}
                            {{--let themeColorForm = $("#theme-color-form");--}}
                            {{--let url  = themeColorForm.attr("action");--}}
                            {{--let postdata = themeColorForm.serialize();--}}
                            {{--$("#theme-color").on('change', function(){--}}
                                {{--console.log("lets go");--}}
                                {{--$.post(url, postdata).done(function () {--}}
                                    {{--$('#color-saved').show();--}}
                                    {{--$('#color-saved').hide(1000);--}}
                                {{--})--}}
                            {{--});--}}
                        {{--</script>--}}

                        <div class="col-md-6">
                            {{--PRIMARY BUSINESS LOGO--}}
                            <p class="text-center">
                                <a class="text-primary btn theme-background" id="update-business-logo" data-target="#business-logo-dropzone" onclick="triggerTargetClick(event, this)">update logo</a>
                            <form action="/business/updateLogo/{{$business->id}}" class="dropzone business-logo-dropzone hide" id="business-logo-dropzone">
                                {{ csrf_field() }}
                                {{ form_method_field("POST") }}
                                <div class="fallback">
                                    <input name="file" type="file" multiple />
                                </div>
                            </form>
                            </p>
                            <div href="{{ $haslogo ? getImage($business->logo_path) : ''}}" class="business-logo-placeholder text-center mb-3" style="background-image: url({{$haslogo ? getImage($business->logo_path) : ''}})" {{$haslogo ? 'data-lity' : ''}}>
                                @if(!$haslogo) <span class="fa fa-photo fa-2x" style="display: block; margin-top: 30%"></span> @endif
                            </div>
                            @if($haslogo)
                                <p class="text-center">
                                    <a class="text-danger" id="delete-business-logo" data-target="#delete-business-logo-form" onclick="triggerTargetSubmit(event, this, false)">remove</a>
                                <form method="post" action="/business/deleteLogo/{{$business->id}}" class="hide" id="delete-business-logo-form">
                                    {{ csrf_field() }}
                                    {{ form_method_field("DELETE") }}
                                </form>
                                </p>
                            @endif
                            {{--PRIMARY BUSINESS LOGO--}}
                            <p class="text-center">*logo will appear in the store front*</p>
                        </div>
                        {{--<div class="col-md-6">--}}
                            {{--PRIMARY BUSINESS PHOTO--}}
                            {{--<p class="text-center">--}}
                                {{--<a class="text-primary btn theme-background" id="update-business_photo" data-target="#business-dropzone" onclick="triggerTargetClick(event, this)">update primary photo</a>--}}
                                {{--<form action="/business/updatePhoto/{{$business->id}}" class="dropzone hide" id="business-dropzone">--}}
                                    {{--{{ csrf_field() }}--}}
                                    {{--{{ form_method_field("POST") }}--}}
                                    {{--<div class="fallback">--}}
                                        {{--<input name="file" type="file" multiple />--}}
                                    {{--</div>--}}
                                {{--</form>--}}
                            {{--</p>--}}
                            {{--<div href="{{ $hasPhoto ? getImage($business->photo_path) : ''}}" class="business-img-placeholder text-center mb-3" style="background-image: url({{$hasPhoto ? getImage($business->photo_path) : ''}})" {{$hasPhoto ? 'data-lity' : ''}}>--}}
                                {{--@if(!$hasPhoto) <span class="fa fa-photo fa-2x" style="display: block; margin-top: 30%"></span> @endif--}}
                            {{--</div>--}}
                            {{--@if($hasPhoto)--}}
                            {{--<p class="text-center">--}}
                                {{--<a class="text-danger" id="delete-business-photo" data-target="#delete-business-photo-form" onclick="triggerTargetSubmit(event, this, false)">remove</a>--}}
                                {{--<form method="post" action="/business/deletePhoto/{{$business->id}}" class="hide" id="delete-business-photo-form">--}}
                                    {{--{{ csrf_field() }}--}}
                                    {{--{{ form_method_field("DELETE") }}--}}
                                {{--</form>--}}
                            {{--</p>--}}
                            {{--@endif--}}
                        {{--PRIMARY BUSINESS PHOTO--}}
                        {{--</div>--}}

                        <div class="col-md-6">
                            <h3 class="text-justify">{{$business->name}}</h3>
                            <h4><i>{{"@".$business->business_handle}}</i></h4>
                            <p class="theme-color"><i>otruvez.com/store/{{$business->business_handle}}</i></p>
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
                        <div class="card-body">
                            <hr>
                            <h3 class="text-justify" data-toggle="collapse" data-target="#redirect-url-info">Redirect Url: <span class="float-right theme-color">What's this?</span> </h3>
                            <p class="theme-color collapse" id="redirect-url-info" >This field is for online businesses who want to use our portal to sell their subscriptions. After a customer completes the process, they will be redirected to this URL. You can also set this field in the <b>API & Online Business Integration</b> Page for any of the services you offer.</p>
                            <p>{{$business->redirect_to ?: 'No url provided'}}</p>
                        </div>

                        <div class="card-footer " style="width: 100%">
                            <button type="button" class="btn-sm theme-background float-left show-sm-modal" data-modal-target="#business-details-{{$business->id}}">Edit Details</button>
                            <a href="/plan/managePlans" class="btn-sm theme-background float-right">manage services</a>
                        </div>

                            <!-- EDIT BUSINESS DETAILS Modal -->
                            @include('modals.bootstrap.edit-business-modal')

                    </div>
        </div>
    </div>


    @endif
@endsection

@section('footer')


    <script src="{{ baseUrlConcat('/js/dropzone.js') }}"></script>
    <script src="{{ baseUrlConcat('/js/dropzone-options.js') }}"></script>
    <script src="{{baseUrlConcat('/js/google-location/set-address.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgE-TwAg_-s8NzBqtdJtkMVlQ86Qf9nho&libraries=places&callback=initAutocomplete"
            async defer></script>


@endsection