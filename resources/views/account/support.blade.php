@extends('layouts.app')
@section('body')
    @include('partials.account-back')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4>Customer Support</h4>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                <li>No special characters may be entered</li>
                            </ul>
                        </div>
                    @endif
                </div>
                    <form class="form-group validate-contact-form" action="/account/contactSupport" method="post">
                        {{csrf_field()}}
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