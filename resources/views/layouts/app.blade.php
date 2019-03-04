<!DOCTYPE html>
<html lang="en">
<head>

    <!-- SITE TITTLE -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Otruvez</title>

    <!-- PLUGINS CSS STYLE -->
    {{--<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Bootstrap -->
    <link href="{{baseUrlConcat('/classimax/plugins/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Owl Carousel -->
    {{--    <link href="{{baseUrlConcat('/classimax/plugins/slick-carousel/slick/slick.css')}}" rel="stylesheet">--}}
    {{--    <link href="{{baseUrlConcat('/classimax/plugins/slick-carousel/slick/slick-theme.css')}}" rel="stylesheet">--}}
<!-- Fancy Box -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lity/2.3.0/lity.min.css">
    <link href="{{baseUrlConcat('/classimax/css/style.css')}}" rel="stylesheet">
    <link href="{{ baseUrlConcat('/css/style.css') }}" rel="stylesheet">



    <!-- FAVICON -->
    <link rel="shortcut icon" href="{{getOtruvezCircleLogoImg()}}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
    @yield('meta')

</head>

<body class="body-wrapper">
{{--<div style="position: absolute; top: 0; left: 0; width: 100%"></div>--}}
@include("loading.loading")

{{--NAV SECTION--}}
<div class="container">
    <div class="row">
        {{--<div class="col-md-12">--}}
        <nav class="navbar navbar-light navbar-expand-md navigation p-4" style="width: 100% !important">

                @if(\Illuminate\Support\Facades\Auth::check())
                <a class="navbar-brand" href="/">
                    <img src="{{baseUrlConcat("/classimax/images/logos/otruvez-logo.png")}}" style="width: 150px; height: auto;">
                </a>
                <button class="navbar-toggler theme-background" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    {!! hasNewNotifications() ? '<span class="fa fa-bell text-danger"></span>' : '<span class="navbar-toggler-icon"></span>' !!}
                </button>
                @else
                <a class="navbar-brand" href="/">
                    <img src="{{baseUrlConcat("/classimax/images/logos/o-logo.png")}}" width="50">
                </a>
                <button class="navbar-toggler theme-background" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" style="margin-left: 62% !important;">
                    {!! hasNewNotifications() ? '<span class="fa fa-bell text-danger"></span>' : '<span class="navbar-toggler-icon"></span>' !!}
                </button>
                @endif


            @if(\Illuminate\Support\Facades\Auth::check())
                <div class="collapse navbar-collapse" style="" id="navbarSupportedContent">
                    <ul class="navbar-nav" >
                        <li class="nav-item">
                            <a class="nav-link" href="/home"><span class="fa fa-search "></span> Find Subscriptions </a>
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
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" data-toggle="collapse" data-target="#dropit" aria-controls="dropit" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="fa fa-question"></span>
                                FAQs
                            </a>
                            <div class="dropdown-menu nav-item" aria-labelledby="navbarDropdown" id="dropit">
                                <a class="dropdown-item" href="/faqs">Consumer FAQs</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/merchant-faqs">Merchant FAQs</a>
                            </div>
                        </li>
                    </ul>
                </div>
            @else
                <div class="collapse navbar-collapse text-center form-inline" id="navbarSupportedContent">
                    <ul class="navbar-nav ">
                        <li class="nav-item">
                            <a class="btn btn-lg white-bg theme-color  nav-link login-button" href="/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-lg white-bg theme-color  nav-link login-button" href="/contact">Contact</a>
                        </li>

                        <li class="nav-item">
                            <a class="btn btn-lg white-bg theme-color  nav-link login-button" href="/register">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-lg white-bg theme-color  nav-link login-button" href="/login">Login</a>
                        </li>

                        <li class="nav-item">
                            <a class="btn btn-lg white-bg theme-color  nav-link login-button" href="/sellYourServices"> <span class="fa fa-shopping-cart "></span> How to sell with Otruvez </a>
                        </li>

                        <li class="nav-item">
                            <a class="btn btn-lg white-bg theme-color  nav-link login-button" href="/home">Find subscriptions</a>
                        </li>
                        <li class="nav-item dropdown">
                            <button class="btn btn-lg white-bg theme-color  nav-link login-button" href="#" data-toggle="collapse" data-target="#dropit" aria-controls="dropit" aria-expanded="false" aria-label="Toggle navigation">
                                FAQs
                            </button>
                            <div class="dropdown-menu nav-item" aria-labelledby="navbarDropdown" id="dropit">
                                <a class="btn btn-lg white-bg theme-color dropdown-item" href="/faqs">Consumer FAQs</a>
                                <div class="dropdown-divider"></div>
                                <a class="btn btn-lg white-bg theme-color dropdown-item" href="/merchant-faqs">Merchant FAQs</a>
                            </div>
                        </li>

                    </ul>

                </div>
                <hr>
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


<!-- JAVASCRIPTS -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script src='https://ajax.aspnetcdn.com/ajax/jquery.validate/1.17.0/additional-methods.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lity/2.3.0/lity.min.js"></script>
<script src="{{ baseUrlConcat('/js/index.js') }}"></script>
<script>
    var screenheight = screen.height + "px";
    $('#landing-page-section-1').css('height', screenheight);
</script>
@yield('footer')

</body>

</html>
