@extends('layouts.app')
@section('body')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4>Are you sure you want to delete your business account?</h4>
                    <p class="text-danger">Note* Some subscribers may be entitled to a refund</p>
                </div>
                <div class="card-body">
                    <h6>If so, <a href="/account/support"><u><b>send us an email</b></u></a> at support@otruvez.com and we will handle that for you. Thanks!</h6>
                <a href="/business" class="btn theme-background text-white pull-left p-1">Back</a>

                    {{--<button class="btn btn-danger pull-right show-sm-modal" data-modal-target="#confirm-delete-business-modal">Delete Account</button>--}}
                </div>
            </div>
        </div>
    </div>
{{--    @include('modals.custom.confirm-delete-business-modal')--}}
@endsection