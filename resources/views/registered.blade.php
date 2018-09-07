@extends('layouts.app')

@section('body')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <form class="card-body">
                    <h3 class="text-center">Thanks!</h3>
                    <h5 class="text-center">You've successfully registered. <br>
                        Please enter the code that we sent to your email to confirm your account</h5>

                    <div class="col-12 p3">

                            <input class="form-control bg-white text-center theme-color" style="font-size: 2rem !important" name="confirmation_code" placeholder="ex: 123456">

                    </div>

                <div class="card-footer text-center">
                    <button class="btn theme-background" type="submit" disabled>Confirm account</button>
                </div>
            </form>
            </div>
        </div>
    </div>

@endsection

@section('footer')
<script>
    $('.footer-bottom').hide();
</script>
@endsection