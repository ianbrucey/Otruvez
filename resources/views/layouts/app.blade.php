<!DOCTYPE html>
<html lang="en">
<head>

    <!-- SITE TITTLE -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Otruvez</title>

    <!-- PLUGINS CSS STYLE -->
    {{--<link href="{{asset('classimax/plugins/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">--}}
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <!-- Bootstrap -->
    <link href="{{asset('classimax/plugins/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('classimax/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- Owl Carousel -->
    <link href="{{asset('classimax/plugins/slick-carousel/slick/slick.css')}}" rel="stylesheet">
    <link href="{{asset('classimax/plugins/slick-carousel/slick/slick-theme.css')}}" rel="stylesheet">
    <!-- Fancy Box -->
{{--    <link href="{{asset('classimax/plugins/fancybox/jquery.fancybox.pack.css')}}" rel="stylesheet">--}}
    {{--<link href="{{asset('classimax/plugins/jquery-nice-select/css/nice-select.css')}}" rel="stylesheet">--}}
    {{--<link href="{{asset('classimax/plugins/seiyria-bootstrap-slider/dist/css/bootstrap-slider.min.css')}}" rel="stylesheet">--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lity/2.3.0/lity.min.css">
    <!-- CUSTOM CSS -->
    <link href="{{asset('classimax/css/style.css')}}" rel="stylesheet">
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">


    <!-- FAVICON -->
    <link href="img/favicon.png" rel="shortcut icon">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <!--<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>-->
    <!--<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>-->
    <![endif]-->

</head>

<body class="body-wrapper">

{{--NAV SECTION--}}
<div class="container">
    <div class="row">
        {{--<div class="col-md-12">--}}
            <nav class="navbar navbar-light navbar-expand-md navigation p-4" style="width: 100% !important">
                <a class="navbar-brand" href="/">
                    <img src="{{asset('/storage/images/logos/otruvez-logo.png')}}" style="width: 150px; height: auto;">
                </a>
                <button class="navbar-toggler theme-background" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    {!! hasNewNotifications() ? '<span class="fa fa-bell text-danger"></span>' : '<span class="navbar-toggler-icon"></span>' !!}
                </button>
                @if(\Illuminate\Support\Facades\Auth::check())
                    <div class="collapse navbar-collapse" style="" id="navbarSupportedContent">
                        <ul class="navbar-nav" >
                            <li class="nav-item">
                                <a class="nav-link" href="/home"><span class="fa fa-search "></span> Search </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{hasNewNotifications() ? "text-danger" : ''}}" href="/account"><span class="fa fa-user-circle "></span> My Account </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/business"> <span class="fa fa-briefcase "></span> Business Account </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/log/out"><span class="fa fa-sign-out "></span> Logout</a>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-4">
                            <li class="nav-item">
                                <a class="nav-link login-button" href="/">How it works</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link login-button" href="/">Contact</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link login-button" href="/register">Register</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link login-button" href="/login">Login</a>
                            </li>
                        </ul>
                    </div>
                @endif
            </nav>
        {{--</div>--}}

    </div>
</div>
{{--NAV SECTION--}}

@yield('header')
@include("alerts.plan-alerts")
        @yield('body')


        <!--============================
=            Footer            =
=============================-->
        @if(!\Illuminate\Support\Facades\Auth::check())
            <!-- Container Start -->
            <!-- Container End -->
        </footer>
        <!-- Footer Bottom -->
        <footer class="footer-bottom">
            <!-- Container Start -->
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <!-- Copyright -->
                        <div class="copyright">
                            <p>Copyright © 2016. All Rights Reserved</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <!-- Social Icons -->
                        <ul class="social-media-icons text-right">
                            <li><a class="fa fa-facebook" href=""></a></li>
                            <li><a class="fa fa-twitter" href=""></a></li>
                            <li><a class="fa fa-pinterest-p" href=""></a></li>
                            <li><a class="fa fa-vimeo" href=""></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Container End -->
            <!-- To Top -->
            <div class="top-to">
                <a id="top" class="" href=""><i class="fa fa-angle-up"></i></a>
            </div>
        </footer>
        @endif

        <!-- JAVASCRIPTS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
{{--        <script src="{{asset('classimax/plugins/jquery/jquery.js')}}"></script>--}}
        <script src="{{asset('classimax/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
{{--        <script src="{{asset('classimax/plugins/tether/js/tether.min.js')}}"></script>--}}
        <script src="{{asset('classimax/plugins/raty/jquery.raty-fa.js')}}"></script>
        <script src="{{asset('classimax/plugins/bootstrap/dist/js/popper.min.js')}}"></script>
        <script src="{{asset('classimax/plugins/bootstrap/dist/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('classimax/plugins/seiyria-bootstrap-slider/dist/bootstrap-slider.min.js')}}"></script>
        <script src="{{asset('classimax/plugins/slick-carousel/slick/slick.min.js')}}"></script>
        <script src="{{asset('classimax/plugins/jquery-nice-select/js/jquery.nice-select.min.js')}}"></script>
        <script src="{{asset('classimax/plugins/fancybox/jquery.fancybox.pack.js')}}"></script>
        <script src="{{asset('classimax/plugins/smoothscroll/SmoothScroll.min.js')}}"></script>
        <script src="{{asset('classimax/js/scripts.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lity/2.3.0/lity.min.js"></script>
<script src="{{ asset('js/stripe.js') }}"></script>
<script src="{{ asset('js/index.js') }}"></script>
@yield('footer')

</body>

</html>
