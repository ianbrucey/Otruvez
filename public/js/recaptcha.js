let canSubmit = false;
window.onloadCallback = function() {
    grecaptcha.render('recaptcha', {
        'sitekey' : '6LdhMW4UAAAAAFACdJlsLj3Z3bwoFgTScs2PjNqR',
        'callback' : recaptchaResponse,
        'expired-callback' : expiredRecaptchaResponse,
        'error-callback' : errorRecaptchaResponse,
    });
};

function recaptchaResponse(token) {
    let data = {
        secret: '6LdhMW4UAAAAAGFcIO72FqWsyIThtH9MNpc6vCP9',
        response: token
    };

    $('#register-button').prop('disabled', false).show();
}

function expiredRecaptchaResponse(token) {
    sendWarning("Due to in activity, you're recaptcha widget has expired. Please refresh the page and try again.");
}

function errorRecaptchaResponse(token) {
    sendWarning("There was a problem loading the recaptcha widget. Please try again when you have better network connectivity");
}