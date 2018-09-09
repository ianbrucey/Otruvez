@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.2.0/dropzone.css">
@endsection

@section('body')

    @include('partials.business-back')


    <div class="container">
        <div class="row "><br><br>
            <h3 class="text-center col-12">Manage your services</h3>
        </div>
    </div>
    <div class="container">
        <div class="row">
            @if(count($plans) < 10 || true)
                <a class="col-md-6 offset-md-3 plan-preview-card my-3 new-plan-card" href="/plan/createService">
                    <p class="text-white">Create a new service</p>
                    <span class="fa fa-plus"></span>
                </a>
            @endif
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            @if(count($plans))
                @foreach($plans as $plan)
                    <div class="col-md-4 plan-preview-card">
                        <div class="card-header">
                            <h6><strong>{{$plan->stripe_plan_name}}</strong></h6>
                            {!! $plan->featured_photo_path == null ? '<p class="text-danger"><span class="fa fa-warning text-danger"></span> Service inactive. please add a featured photo</p>' : '<p class="text-info">active</p>'!!}
                            {{--If photo is null, show span element, else show the actual photo--}}
                        </div>
                        <div class="card-body">
                            <div class="general-photo-container">
                                <button class="btn-sm theme-background text-white show-sm-modal m-1" data-toggle="modal" data-modal-target="#plan-gallery-{{$plan->id}}" >Edit Photos</button>
                                <p>Featured Photo</p>
                                @if(!$plan->featured_photo_path)
                                    <span class="fa fa-photo fa-3x text-danger"></span>
                                @else
                                    {{--<img src="{{getImage($plan->featured_photo_path)}}" width="48" height="48" style="display: inline-block" href="{{getImage($plan->featured_photo_path)}}" data-lity>--}}
                                    <div class="photo-preview">
                                        <img src="{{getImage($plan->featured_photo_path)}}"  style="width: 100%; vertical-align: middle !important;" href="" data-lity>
                                    </div>
                                @endif
                            </div>

                            {{--The div below should either show a photo, or the span element--}}
                            <div class="general-photo-container" style="margin-bottom: -10px">
                                {{--<hr>--}}
                                <p>{{count($plan->photos)}}/4 gallery photos</p>
                                @for($i = 0; $i < $maxGalleryCount; $i++)
                                    @php
                                        $hasGalleryPhoto = isset($plan->photos[$i]);
                                        $path    = $hasGalleryPhoto ? $plan->photos[$i]->path : '';
                                    @endphp
                                    @if($hasGalleryPhoto)
                                        {{--<div style="width: 40px; height: 40px; background: url({{getImage($path)}}) no-repeat; background-size: contain; vertical-align: middle !important; border-radius: 5%; border: 1px solid purple; display: inline-block" href="{{getImage($path)}}" data-lity>--}}

                                        {{--</div>--}}
                                        <div class="photo-preview">
                                            <img src="{{ getImage($path) }}"  style="width: 100%; vertical-align: middle !important;" href="" data-lity>
                                        </div>
                                    @else
                                        <span class="fa fa-2x fa-picture-o"></span>
                                    @endif
                                @endfor
                            </div>
                            <hr>
                        </div>

                        <div class="row plan-preview-card-footer" style="width: 100%; margin-top: -15px; margin-left: 10px">


                                {{--<div style="width: 100%" class="text-center">--}}
                                    <div class="col-4 show-sm-modal" data-toggle="modal" data-modal-target="#plan-details-{{$plan->id}}">
                                        {{--view details--}}
                                        <span class="fa fa-eye fa-2x"></span>
                                    </div>
                                    <div class="col-4 show-sm-modal" data-toggle="modal" data-modal-target="#plan-edit-{{$plan->id}}">
                                        <span class="fa fa-pencil fa-2x"></span>
                                    </div>
                                    <div class="col-4 delete-plan" data-target="#delete-plan-form-{{$plan->id}}" data-plan-name="{{$plan->stripe_plan_name}}" onclick="deletePlan(event, this)">
                                        <form action="/plan/delete/{{$plan->id}}" method="POST" id="delete-plan-form-{{$plan->id}}">
                                            {{method_field('DELETE')}}
                                            {{csrf_field()}}
                                            <span class="fa fa-trash fa-2x"></span>
                                        </form>
                                    </div>

                                {{--</div>--}}
                        </div>

                        <div class=" plan-preview-card-footer mt-4">


                            {{--<div style="width: 100%" class="text-center">--}}
                            <a href="/plan/apiSetup/{{$plan->id}}" class="btn theme-background" style="width: 100%;">API & Online Business integration</a>

                            {{--</div>--}}
                        </div>
                    </div>
                    @include('modals.bootstrap.edit-plan-modal')
                    @include('modals.bootstrap.plan-details-modal')
                    @include('modals.bootstrap.plan-gallery-modal')

                @endforeach
            @endif

        </div>
    </div>

    @include('modals.custom.create-plan-modal')


    <!-- CREATE BUSINESS Modal -->

@endsection

@section('footer')

    <script src="{{ baseUrlConcat('/js/index.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.2.0/min/dropzone.min.js"></script>
    <script src="{{ baseUrlConcat('/js/dropzone-options.js') }}"></script>
    <script src="{{baseUrlConcat('/js/google-location/set-address.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuTqYHpeNjvxPvYQZG7JueMS9tClD7yVY&libraries=places&callback=initAutocomplete" async defer></script>

@endsection