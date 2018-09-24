/**
 * Created by macbook on 11/9/17.
 */
let currentLocation = window.location.href;
let loadingPhoto = $('#loading-photo');
let submittingLoader = $('#submitting');
const validFileExtensions = ['jpeg' , 'jpg', 'png', 'bmp', 'gif'];
const maxUploadSize = 1024000;

$('#searchField').keyup(function(event) {
    if($(this).val().trim() == '' || $(this).val().length < 2) {
        $("#searchField-btn").prop('disabled', true);
        return;
    } else {
        $("#searchField-btn").prop('disabled', false);
    }
    if (event.keyCode === 13) {
        $("#searchField-btn").trigger('click');
    }
});

$('.show-sm-modal').on('click', function(){
    var target = $(this).attr('data-modal-target');
    $(target).fadeIn(500);
    console.log(target);
});

$('.hide-sm-modal').on('click', function(){
    $('.sm-modal').hide(500);

    if($(this).parents('.sm-modal').hasClass('refresh')) {
        window.location.href = currentLocation;
    }
});

$('.sm-modal').on('click', function(e){
    if (e.target !== this){
        return;
    }
    $(this).hide(500);
    if($(this).hasClass('refresh')) {
        window.location.href = currentLocation;
    }
});

$('.has-business-hours').on('change', function(){
    if($(this).is(':checked'))
    {
        $('.business-hours').show();
    } else {
        $('.business-hours').hide();
    }
});

$('#redirect-to-form').on('submit', function (e) {
    e.preventDefault();
    let formData = $(this).serialize();
    $.post('/business/updateRedirectTo', formData).done(function (data) {
        sendSuccess(data)
    }).fail(function (jqXHR, textStatus, errorThrown) {
        sendWarning("Please enter a valid url");
    });
});



function triggerTargetClick(e, obj) {
    e = e || window.event;
    e.preventDefault();
    $($(obj).attr('data-target')).trigger('click');
}

function triggerTargetHref(e, obj) {
    $('#submitting').fadeIn(250);
    e = e || window.event;
    e.preventDefault();
    window.location.href = $(obj).attr('data-href');
}

function triggerTargetSubmit(e, obj, ajaxSubmit) {
    e = e || window.event;
    e.preventDefault();
    let form = $($(obj).attr('data-target'));
    let paginationIndex = $(obj).attr('data-from');
    if(paginationIndex > 0) {
        form.append('<input type="hidden" name="from" value="'+paginationIndex+'">');
    }
    if(ajaxSubmit) {
        if(form.find('#subject').val() == '' || form.find('#body').val() == ''){
            sendWarning("both fields are required");
            return;
        }
        $('#submitting').fadeIn(500);
        let currentLocation = window.location.href;
        let postdata    = form.serialize();
        let url         = form.attr('action');
        $.post(url, postdata);

        setTimeout(function () {
            window.location.href = "/business?messageSent";
        }, 2000);
    } else {
        $('#submitting').fadeIn(500);
        form.submit();
    }
}


$('.which_usage_interval').on('change', function () {
    let zis = $(this);
    if(zis.prop('checked')) {
        $(zis.attr('data-input')).prop('disabled', false);
        $(zis.attr('data-input-other')).prop('disabled', true).val('');
        $(zis.attr('data-label')).addClass('theme-color');
        $(zis.attr('data-label-other')).removeClass('theme-color').css('color', 'lightgrey');
        console.log('works');
    }
});

function deletePlan(e, obj) {
    $.confirm({
        icon: 'fa fa-warning text-danger',
        title: 'Are you sure?',
        content: 'Do you want to delete your plan: "' + $(obj).attr("data-plan-name") + '"',
        buttons: {
            delete: {
                btnClass: 'btn-danger',
                action: function() {
                    triggerTargetSubmit(e, obj, false);
                }
            },

            cancel: {
                btnClass: 'theme-background',
            }
        }
    });
}

function cancelSubscription(e, obj) {
    $.confirm({
        icon: 'fa fa-warning text-danger',
        title: 'Are you sure?',
        content: 'Do you want to delete your subscription: "' + $(obj).attr("data-subscription-name") + '"',
        buttons: {
            delete: {
                btnClass: 'btn-danger',
                action: function() {
                    triggerTargetSubmit(e, obj, false);
                }
            },

            cancel: {
                btnClass: 'theme-background',
            }
        }
    });
}


$(document).find('.Button-animationWrapper-child--primary').on('click', function (e) {
    e.preventDefault();
    alert();
});

$(document).ready(function () {



    // LOGIN VALIDATION
    $('.validate-login').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 8,
                password: true

            }
        },
        invalidHandler: function(event, validator) {
            // 'this' refers to the form
            var errors = validator.numberOfInvalids();
            if (errors) {
                var message = errors == 1
                    ? 'You missed 1 field. It has been highlighted'
                    : 'You missed ' + errors + ' fields. They have been highlighted';
                $("div.error span").html(message);
                $("div.error").show().css('color','red');
            } else {
                $("div.error").hide();
            }
        },
        submitHandler: function (form) {
            $('#submitting').fadeIn(500);
            form.submit();
        }
    });
    // LOGIN VALIDATION

    // REGISTER VALIDATION
    $('.validate-register').validate({
        rules: {
            first: {
                required: true,
                lettersonly: true

            },
            last: {
                required: true,
                lettersonly: true
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 8,
                password: true
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                password: true
            }
        },
        invalidHandler: function(event, validator) {
            // 'this' refers to the form
            let errors = validator.numberOfInvalids();
            if (errors) {
                let message = errors === 1
                    ? 'You missed 1 field. It has been highlighted'
                    : 'You missed ' + errors + ' fields. They have been highlighted';
                $("div.error span").html(message);
                $("div.error").show().css('color','red');
            } else {
                $("div.error").hide();
            }
        },
        submitHandler: function (form) {
            $('#submitting').fadeIn(500);
            form.submit();
        }
    });
    // REGISTER VALIDATION

    $('.validate-contact-form').validate({
        rules: {
            email: {
                email: true
            },
            subject: {
                required: true,
                minlength: 3,
                alphaNumericSpace: true
            },
            body: {
                required: true,
                minlength: 10,
                alphaNumericSpace: true
            }
        },
        invalidHandler: function(event, validator) {
            // 'this' refers to the form
            var errors = validator.numberOfInvalids();
            if (errors) {
                var message = errors == 1
                    ? 'You missed 1 field. It has been highlighted'
                    : 'You missed ' + errors + ' fields. They have been highlighted';
                $("div.error span").html(message);
                $("div.error").show().css('color','red');
            } else {
                $("div.error").hide();
            }
        },
        submitHandler: function (form) {
            $('#submitting').fadeIn(500);
            form.submit();
        }
    });

    $('.validate-create-business').validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                alphaNumericSpace: true
            },
            email: {
                required: true,
                email: true,
                minlength: 10
            },
            phone: {
                required: false,
                minlength: 10,
                phoneUS: true
            },
            description: {
                required: true,
                minlength: 10,
                alphaNumericSpace: true
            },
            redirect_to: {
                required: false,
                url: true
            },
            monday: {
                required: false,
                alphaNumericSpace: true
            },
            tuesday: {
                required: false,
                alphaNumericSpace: true
            },
            wednesday: {
                required: false,
                alphaNumericSpace: true
            },
            thursday: {
                required: false,
                alphaNumericSpace: true
            },
            friday: {
                required: false,
                alphaNumericSpace: true
            },
            saturday: {
                required: false,
                alphaNumericSpace: true
            },
            sunday: {
                required: false,
                alphaNumericSpace: true
            }
        },
        invalidHandler: function(event, validator) {
            // 'this' refers to the form
            var errors = validator.numberOfInvalids();
            if (errors) {
                var message = errors == 1
                    ? 'You missed 1 field. It has been highlighted'
                    : 'You missed ' + errors + ' fields. They have been highlighted';
                $("div.error span").html(message);
                $("div.error").show().css('color','red');
            } else {
                $("div.error").hide();
            }
        },
        submitHandler: function (form) {
            $('#submitting').fadeIn(500);
            form.submit();
        }
    });


    $('.validate-create-service').validate({
        rules: {
            stripe_plan_name: {
                required: true,
                alphaNumericSpace: true
            },
            month_price: {
                digits: true,
            },
            year_price: {
                digits: true
            },
            use_limit_month: {
                digits: true,
                required: false
            },
            use_limit_year: {
                digits: true,
                required: false
            },
            description: {
                required: true,
                minlength: 10,
                alphaNumericSpace: true
            }
        },
        invalidHandler: function(event, validator) {
            // 'this' refers to the form
            var errors = validator.numberOfInvalids();
            if (errors) {
                var message = errors == 1
                    ? 'You missed 1 field. It has been highlighted'
                    : 'You missed ' + errors + ' fields. They have been highlighted';
                $("div.error span").html(message);
                $("div.error").show().css('color','red');
            } else {
                $("div.error").hide();
            }
        },
        submitHandler: function (form) {
            $('#submitting').fadeIn(500);
            if(!$('#month_price').val() && !$('#year_price').val()) {
                alert("At least one price is required");
                submittingLoader.hide();
                $("#price-msg").removeClass("theme-color").addClass("text-danger");
                scrollToElement('#pricing-info');
                return false;
            }
            if(!$('#featured-photo').val()) {
                sendWarning("A featured photo is required. Please add one to proceed.");
                $('#create-service-step1').show();
                $('#create-service-step2').hide();
                $('.create-service-next-step').prop('disabled', true);
                submittingLoader.hide();
                return false;
            }
            form.submit();
        }
    });

    $('.validate-edit-plan').validate({
        rules: {
            stripe_plan_name: {
                required: true,
                alphaNumericSpace: true
            },
            description: {
                required: true,
                minlength: 10,
                alphaNumericSpace: true

            }
        },
        invalidHandler: function(event, validator) {
            // 'this' refers to the form
            var errors = validator.numberOfInvalids();
            if (errors) {
                var message = errors == 1
                    ? 'You missed 1 field. It has been highlighted'
                    : 'You missed ' + errors + ' fields. They have been highlighted';
                $("div.error span").html(message);
                $("div.error").show().css('color','red');
            } else {
                $("div.error").hide();
            }
        },
        submitHandler: function (form) {
            $('#submitting').fadeIn(500);
            form.submit();
        }
    });

    $('.validate-delete-account').validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        invalidHandler: function(event, validator) {
            // 'this' refers to the form
            var errors = validator.numberOfInvalids();
            if (errors) {
                var message = errors == 1
                    ? 'You missed 1 field. It has been highlighted'
                    : 'You missed ' + errors + ' fields. They have been highlighted';
                $("div.error span").html(message);
                $("div.error").show().css('color','red');
            } else {
                $("div.error").hide();
            }
        },
        submitHandler: function (form) {
            $('#submitting').fadeIn(500);
            form.submit();
        }
    });

    $('.validate-review').validate({
        rules: {
            body: {
                required: true,
                alphaNumericSpace: true
            }
        },
        invalidHandler: function(event, validator) {
            // 'this' refers to the form
            var errors = validator.numberOfInvalids();
            if (errors) {
                var message = errors == 1
                    ? 'You missed 1 field. It has been highlighted'
                    : 'You missed ' + errors + ' fields. They have been highlighted';
                $("div.error span").html(message);
                $("div.error").show().css('color','red');
            } else {
                $("div.error").hide();
            }
        },
        submitHandler: function (form) {
            $('#submitting').fadeIn(500);
            form.submit();
        }
    });

    $('.validate-redirect').validate({
        rules: {
            redirect_to: {
                required: true,
                url: true
            }
        },
        invalidHandler: function(event, validator) {
            // 'this' refers to the form
            var errors = validator.numberOfInvalids();
            if (errors) {
                var message = errors == 1
                    ? 'You missed 1 field. It has been highlighted'
                    : 'You missed ' + errors + ' fields. They have been highlighted';
                $("div.error span").html(message);
                $("div.error").show().css('color','red');
            } else {
                $("div.error").hide();
            }
        },
        submitHandler: function (form) {
            $('#submitting').fadeIn(500);
            form.submit();
        }
    });

    $('.validate-checkin').validate({
        rules: {
            checkin_code: {
                required: true,
                digits: true
            }
        },
        invalidHandler: function(event, validator) {
            // 'this' refers to the form
            var errors = validator.numberOfInvalids();
            // if (errors) {
            //     var message = errors == 1
            //         ? 'You missed 1 field. It has been highlighted'
            //         : 'You missed ' + errors + ' fields. They have been highlighted';
            //     $("div.error span").html(message);
            //     $("div.error").show().css('color','red');
            //     $('#confirm-checkin-btn').hide();
            // } else {
            //     $("div.error").hide();
            //     $('#confirm-checkin-btn').show();
            // }
        },
        submitHandler: function (form) {
            $('#submitting').fadeIn(500);
            form.submit();
        }
    });

    $.validator.addMethod("alphaNumericSpace", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-\s.]+$/i.test(value);
    }, "field must contain only letters, numbers, or dashes.");

    $.validator.addMethod("password", function(value, element) {
        return this.optional(element) || /^(?=.*[\d])(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*])[\w!@#$%^&*]{8,}$/i.test(value);
    }, "Your password does not meet our requirements");

});

// may keep for smoother transition
$( window ).ready(function() {
    // $('#blanket').show();
    $('#blanket').fadeOut(500);
});
// When clicking here, we will trigger the dropzone that
// lets us choose a NEW FEATURED PHOTO for the the PLAN

function sendWarning(msg) {
    return $.confirm({
        icon: 'fa fa-warning text-danger',
        title: '',
        content: msg,
        buttons: {
            ok: {
                btnClass: 'theme-background',
            }
        }
    });
}

function sendSuccess(msg) {
    return $.confirm({
        icon: 'fa fa-star theme-color',
        title: '',
        content: msg,
        buttons: {
            ok: {
                btnClass: 'theme-background',
            }
        }
    });
}

function scrollToElement(elId) {
    $('html, body').animate({
        scrollTop: ($(elId).offset().top)
    },500);
}

function isValidImage(file) {
    let valid = true;
    let t = file.type.split('/').pop().toLowerCase();
    if ($.inArray(t, validFileExtensions) < 0) {
        sendWarning('Please select a valid image file: <b class="text-danger">' + file.name + '<b>');
        valid = false;
    }
    if (file.size > maxUploadSize) {
        sendWarning('Max Upload size is 1MB only: <b class="text-danger">' + file.name + '<b>');
        valid = false;
    }
    return valid;
}

function copyText(self) {
    /* Get the text field */
    let thisObj = $(self);
    let copyText = thisObj.children('textarea');

    /* Select the text field */
    copyText.select();

    /* Copy the text inside the text field */
    document.execCommand("copy");
    thisObj.find('.copied-msg').show();
    thisObj.find('.copied-msg').fadeOut(1500);

}
