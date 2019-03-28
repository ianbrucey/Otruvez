$('.checkin').on('click', function () {
    let zis     = $(this);
    let planId  = zis.attr('data-plan-id');

    let subscriptionId = zis.attr('data-subscription-id');
    let url = "/subscription/checkin/"+planId+"/"+subscriptionId;
    let checkinModal = $('#checkin-'+subscriptionId);
    let postdata = {
        "planId": planId,
        "subscriptionId": subscriptionId
    };

    $.post(url, postdata).done(function (data) {
        // checkinModal.find('.checkin-code').text(data);
        swal(data,"present this 5 digit code to the service provider", "success");
    });

});

$('.validate-checkin').on('submit', function(event){
    if(event.keyCode === 13) {
        event.preventDefault();
        return false;
    }
});

$('.confirm-checkin').on('click', function (event) {
    let zis     = $(this);
    if($('#checkin-code').val() == '') {
        $('.checkin-error-message').show().text('Checkin code required');
        $('.sm-modal').hide();
        return false;
    } else {
        $('.checkin-error-message').hide();
    }

    let subscriptionId = zis.attr('data-subscription-id');
    let url = "/subscription/confirmCheckin/"+subscriptionId;
    let checkinCard = $('#confirm-checkin-card-'+subscriptionId);
    let checkinForm = $('.confirm-checkin-form-'+subscriptionId);
    let checkinModal = $('#confirm-checkin-modal-'+subscriptionId);
    let responseContainer = $('#confirm-checkin-response-container-'+subscriptionId);
    let postdata = checkinForm.serialize();
    // response is wrong. got successful response on bad code
    $.post(url, postdata).done(function (data) {
        if(data == 1){
            checkinCard.hide();
            checkinModal.hide();
            sendSuccess("The customer is checked in! <br> They may now use your service.<hr>An email has been sent to them for confirmation");
        } else {
            checkinModal.hide();
            sendWarning("Check-in Failed. Please double check that the code is valid.<br> If it is, the user has reached their limit for the time period.");

        }
    });

});

