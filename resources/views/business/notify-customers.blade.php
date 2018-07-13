@extends('layouts.app')
@section('body')

    @include('partials.business-back')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4>Send a message to your customers</h4>
                </div>
                <form class="form-group validate-message" action="/business/notifyCustomers" method="post" id="notify-customer-form">
                    {{csrf_field()}}
                    <label for="subject">Subject</label>
                    <input type="text"  class="form-control bg-white" name="subject" id="subject" placeholder="">

                    <hr>
                    <label for="message">Message</label>
                    <textarea class="form-control bg-white" name="body" id="body" placeholder="" rows="5" cols="50"></textarea>
                    <input type="hidden" name="type" value="support">
                    <hr>
                    <button type="button" class="btn theme-background" data-target="#notify-customer-form" onclick="triggerTargetSubmit(event, this, true)">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection