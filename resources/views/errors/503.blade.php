@extends('layouts.app')
@section('body')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="">
                <h1 class="text-center"><img src="{{getOtruvezLogoImg()}}" width="200"></h1>
                <div class="theme-color text-center">
                    <h2 class="text-center">We'll be back!</h2>
                    <h4 class="text-center ">We're under construction but we'll notify you when we're back up. Thanks.
                    </h4>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = function(){document.getElementsByTagName('footer')['0'].style.display = "none";}
    </script>
@endsection