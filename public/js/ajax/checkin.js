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
        checkinModal.find('.checkin-code').text(data);
    });

});

$('.confirm-checkin').on('click', function () {
    let zis     = $(this);
    let subscriptionId = zis.attr('data-subscription-id');
    let url = "/subscription/confirmCheckin/"+subscriptionId  ;
    let checkinCard = $('#confirm-checkin-card-'+subscriptionId);
    let checkinForm = $('.confirm-checkin-form-'+subscriptionId);
    let responseContainer = $('#confirm-checkin-response-container-'+subscriptionId);
    let postdata = checkinForm.serialize();

    $.post(url, postdata).done(function (data) {
        if(data){
            responseContainer.html('<h3 class="text-success text-center">The customer is confirmed! <br> They may use your service.<hr>An email has been sent to their email for confirmation</h3>');
            checkinCard.hide();
        } else {
            responseContainer.html('<h3 class="text-success text-center">Check-in Failed. Please double check that the code is valid. If it is, the user has reached their limit for the time period.</h3>');
        }
    });

});

