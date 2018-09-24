@extends('layouts.app')
@section('body')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4>Are you sure you want to delete your account?</h4>
                </div>
                <div class="card-body">
                <a href="/account" class="btn-sm theme-background text-white pull-left">Back</a>

                    <button class="btn-sm btn-danger  pull-right show-sm-modal" data-modal-target="#confirm-delete-account-modal">Delete Account</button>
                </div>
            </div>
        </div>
    </div>
    @include('modals.custom.confirm-delete-account-modal')
@endsection