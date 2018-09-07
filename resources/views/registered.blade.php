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

                            <input class="form-control bg-white text-center theme-color" style="font-size: 2rem !important" name="token" placeholder="ex: 123456">

                    </div>

                <div class="card-footer text-center">
                    <button class="btn theme-background" type="button" disabled>Confirm account</button>
                </div>
            </form>
            </div>
        </div>
    </div>

@endsection

@section('footer')
<script>
    let formData = $('form').serialize();
    $.post('/validateToken', formData).done(function (data) {
        if(data === -1) {
            sendWarning("Please do not tamper with our systems or we will report you to the authorities");
        } else if(data === 0) {
            sendWarning("Your code was incorrect. Please check the email again or click the \"resend\" button to get a new code")
        } else if(data === 1) {
            sendSuccess("Success! you will be redirected shortly. If you are not redirected, simply refresh your page");
        } else if(data === 2) {
            sendWarning("Please contact us about your account");
        }
    });
    $('.footer-bottom').hide();
</script>
@endsection