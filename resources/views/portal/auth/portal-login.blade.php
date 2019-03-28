@extends('layouts.portal-app')

@section('body')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-heading text-center"><img src="{{getOtruvezLogoImg()}}" width="100"></h3>
                    <h3 class="card-heading text-center">Login</h3>
                </div>

                <div class="card-body">
                    @include('partials.social.portal-auth-buttons')

                    <form class="form-horizontal validate-login" method="POST" action="{{ secureUrl(route('login')) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="businessId" value="{{$businessId}}">
                        <input type="hidden" name="stripeId" value="{{$stripeId}}">
                        <input type="hidden" name="apiKey" value="{{$apiKey}}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-12 text-left control-label">E-Mail Address</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') ?: $customerEmail ?: '' }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-12 text-left control-label">Password</label>

                            <div class="col-md-12">
                                @include('partials.password-requirements')
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn theme-background">
                                    Login
                                </button>
                                <a type="button" class="btn-sm bg-white theme-color float-right" href="{{$registerRoute}}">
                                    Register instead
                                </a>
                                <hr>

                                <a class="btn-sm btn-link theme-color" href="{{ secureUrl(route('password.request')) }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
