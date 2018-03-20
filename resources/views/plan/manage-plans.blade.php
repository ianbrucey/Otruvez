@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.2.0/dropzone.css">
@endsection

@section('body')

    @include('partials.back')


    <div class="container">
        <div class="row "><br><br>
            <h3 class="text-center col-12 card-header">Manage your services</h3>
        </div>
    </div>
    <div class="container">
        <div class="row">
            @if(count($plans) < 10)
                <div class="col-md-6 offset-md-3 plan-preview-card my-3 new-plan-card show-sm-modal" data-modal-target="#createPlan">
                    <h4><strong>Click here to create a new service</strong></h4>
                    <span class="fa fa-plus fa-2x"></span>
                </div>
            @endif
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            @if(count($plans))
                @foreach($plans as $plan)
                    <div class="col-md-4 plan-preview-card">
                        <div class="card-body">
                            <h3><strong>{{$plan->stripe_plan_name}}</strong></h3>
                            <p>{{$plan->featured_photo_path == null ? '0/1' : '1/1'}} Featured photo</p>
                            <p>{{count($plan->photos)}}/4 gallery photos</p>

                        </div>
                        <div class="row plan-preview-card-footer" style="width: 100%; margin: 0">
                                {{--<div style="width: 100%" class="text-center">--}}
                                    <div class="col-3 show-sm-modal" data-toggle="modal" data-modal-target="#plan-details-{{$plan->id}}">
                                        {{--view details--}}
                                        <span class="fa fa-eye fa-2x"></span>
                                    </div>
                                    <div class="col-3 show-sm-modal" data-toggle="modal" data-modal-target="#plan-edit-{{$plan->id}}">
                                        <span class="fa fa-pencil fa-2x"></span>
                                    </div>
                                    <div class="col-3 show-sm-modal" data-toggle="modal" data-modal-target="#plan-gallery-{{$plan->id}}">
                                        <span class="fa fa-photo fa-2x"></span>
                                    </div>
                                    <div class="col-3" data-target="#delete-plan-form-{{$plan->id}}" onclick="triggerTargetSubmit(event, this)">
                                        <form action="/plan/delete/{{$plan->id}}" method="POST" id="delete-plan-form-{{$plan->id}}">
                                            {{method_field('DELETE')}}
                                            {{csrf_field()}}
                                            <span class="fa fa-trash fa-2x"></span>
                                        </form>
                                    </div>

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

    <script src="{{ asset('js/index.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.2.0/min/dropzone.min.js"></script>
    <script src="{{ asset('js/dropzone-options.js') }}"></script>
    <script src="{{asset('js/google-location/set-address.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuTqYHpeNjvxPvYQZG7JueMS9tClD7yVY&libraries=places&callback=initAutocomplete" async defer></script>

@endsection