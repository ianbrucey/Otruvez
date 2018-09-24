@extends('layouts.app')
@section('body')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-heading text-center"><img src="{{getOtruvezLogoImg()}}" width="100"></h3>
                    <h3 class="card-heading text-center">Contact us</h3>
                    @include('errors.request-errors')
                </div>
                <form class="form-group validate-contact-form" action="/contactUs" method="post">
                    {{csrf_field()}}
                    <label for="subject">Your email</label>
                    <input type="email"  class="form-control bg-white" name="email" placeholder="you@domain.com">
                    <label for="subject">Subject</label>
                    <input type="text"  class="form-control bg-white" name="subject" placeholder="what is this regarding?">

                    <hr>
                    <label for="message">Message</label>
                    <textarea class="form-control bg-white" name="body" placeholder="How may we help you?" rows="5" cols="50"></textarea>
                    <input type="hidden" name="type" value="support">
                    <hr>
                    <button type="submit" class="btn theme-background">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection