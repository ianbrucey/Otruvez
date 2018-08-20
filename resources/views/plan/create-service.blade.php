@extends('layouts.app')

@section('body')

<h3 class="text-center">Create a Service<hr></h3>

@include('partials.plan.create-plan-step1')

@include('partials.plan.create-plan-step2')


@endsection


@section('footer')
    <script src="{{ baseUrlConcat('/js/index.js') }}"></script>
    <script src="{{ baseUrlConcat('/js/dropzone.js') }}"></script>
    <script src="{{ baseUrlConcat('/js/create-service.js') }}"></script>
@endsection