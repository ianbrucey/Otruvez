@extends('layouts.app')

@section('body')

<h3 class="text-center">Create a Service<hr>
    @include('errors.request-errors')
</h3>

@include('partials.plan.create-plan-step1')

@include('partials.plan.create-plan-step2')


@endsection


@section('footer')
    <script src="{{ baseUrlConcat('/js/create-service.js') }}"></script>
@endsection