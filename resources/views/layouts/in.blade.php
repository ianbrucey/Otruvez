<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Otruvez') }}</title>

    <!-- Styles -->
    <link href="{{ baseUrlConcat('/css/app.css') }}" rel="stylesheet">
    <link href="{{ baseUrlConcat('/css/style.css') }}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lity/2.3.0/lity.min.css">
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    @yield('header')
    {{--<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>--}}
</head>
<body style="background: cornflowerblue">
<div class="sm-modal" id="loading">
    <h3 class="text-center text-default" style="margin: auto">
        LOADING...
    </h3>
</div>
<nav class="navbar navbar-default navbar-static-top">
    @if (Auth::check())
        <div class="container-fluid">
            <div class="row text-center">
                <a href="{{ route('home') }}" class="col-xs-4 btn btn-lg"><span class="fa fa-home fa-2x"></span></a>
                <a href="{{ route('user') }}" class="col-xs-4 btn btn-lg"><span class="fa fa-user fa-2x"></span> </a>
                <a href="{{ route('business') }}" class="col-xs-4 btn btn-lg"><span class="fa fa-briefcase fa-2x"></span> </a>
            </div>
        </div>
    @endif
</nav>



@yield('body')

@yield('footer')

<!-- Scripts -->
<script src="{{ baseUrlConcat('/js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lity/2.3.0/lity.min.js"></script>

</body>
</html>
