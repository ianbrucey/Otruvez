@extends('layouts.app')
@section('body')

    @include('partials.business-back')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4>Send a message to your customers</h4>
                </div>
                <form class="form-group validate-message" action="/business/notifyCustomers" method="post">
                    {{csrf_field()}}
                    <label for="subject">Subject</label>
                    <input type="text"  class="form-control bg-white" name="subject" placeholder="">

                    <hr>
                    <label for="message">Message</label>
                    <textarea class="form-control bg-white" name="body" placeholder="" rows="5" cols="50"></textarea>
                    <input type="hidden" name="type" value="support">
                    <input type="hidden" name="type" value="support">
                    <input type="hidden" name="type" value="support">
                    <input type="hidden" name="type" value="support">
                    <hr>
                    <button type="submit" class="btn theme-background">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection