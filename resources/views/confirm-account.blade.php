@extends('layouts.app')

@section('body')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <form class="card-body" id="validate-token-form">
                    <h3 class="text-center">Thanks!</h3>
                    <h5 class="text-center">You've successfully registered. <br>
                        Please enter the code that we sent to your email to confirm your account</h5>

                    <div class="col-12 p3">
                            <input class="form-control bg-white text-center theme-color" style="font-size: 2rem !important" name="activation_token" id="activation-token">
                            {{csrf_field()}}
                    </div>

                <div class="card-footer text-center">
                    <button class="btn theme-background" type="submit" id="submit-token-button" disabled>Confirm account</button>
                </div>
            </form>
            </div>
        </div>
    </div>

@endsection

@section('footer')
<script>

    $('#activation-token').on('keyup', function () {
        if($(this).val().length < 6) {
            $('#submit-token-button').prop('disabled', true);
        } else {
            $('#submit-token-button').prop('disabled', false);
        }
    });

    $('#validate-token-form').on('submit', function (e) {
        e.preventDefault();
        submittingLoader.show();
        let form = $(this);
        let formData = form.serialize();
        $.post('/validateToken', formData)
        .fail(function(xhr, status, error){
            sendWarning("There was a problem with your request, please try again later");
            submittingLoader.fadeOut();
        })
        .done(function (data) {
            if(data.tokenStatus === -1) {
                sendWarning("Please do not tamper with our systems or we will report you to the authorities");
            } else if(data.tokenStatus === 0) {
                sendWarning("Your code was incorrect. We've sent you a new code to your email. Please get that code and try again")
                // enable resend button
            } else if(data.tokenStatus === 1) {
                let timer = 3;
                sendSuccess("Success! you will be redirected shortly in "+timer+" seconds. If you are not redirected, simply refresh your page");
                setInterval(function () {
                    --timer;
                },3000);
                setTimeout(function () {
                    window.location.reload();
                }, 3000)
            } else if(data.tokenStatus === 2) {
                sendWarning("You've been locked out. Please contact us about your account.");
            }
            form[0].reset();
            submittingLoader.fadeOut();
        });
    });
    $('.footer-bottom').fadeOut();
</script>
@endsection