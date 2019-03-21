/**
 * Created by macbook on 11/9/17.
 */
let currentLocation = window.location.href;
let loadingPhoto = $('#loading-photo');
let submittingLoader = $('#submitting');
const validFileExtensions = ['jpeg' , 'jpg', 'png', 'bmp', 'gif'];
const maxUploadSize = 4 * 1024000;

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
        let postdata    = form.serialize();
        let url         = form.attr('action');
        $.post(url, postdata);

        setTimeout(function () {
            window.location.href = "/business";
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
        content: 'Do you want to delete this subscription?',
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


    $('#searchField').on('input', function () {
        if(!$.isNumeric($('#lat').val()) || !$.isNumeric($('#lng').val())) {
            sendWarning("oops! your location was not detected. please set it before searching");
            document.getElementById('autocomplete').style.border = "3px solid red";
        } else {
            document.getElementById('autocomplete').style.border = "";
        }
    });

    // LOGIN VALIDATION
    $('.validate-login').validate({
        rules: {
            email: {
                email: true,
                required: true,
            },
            password: {
                password: true,
                minlength: 8,
                required: true,
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
                lettersonly: true,
                required: true,
            },
            last: {
                lettersonly: true,
                required: true,
            },
            email: {
                email: true,
                required: true,
            },
            password: {
                password: true,
                minlength: 8,
                required: true,


            },
            password_confirmation: {
                password: true,
                minlength: 8,
                required: true,


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
                minlength: 3,
                required: true,

            },
            body: {
                minlength: 10,
                required: true,
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
                titleNameRegex: true,
                minlength: 3,
                required: true,
            },
            choose_business_handle: {
                handle: true,
                minlength: 2,
                required: true
            },
            email: {
                email: true,
                required: true,
            },
            phone: {
                phoneUS: true,
                minlength: 10,
                required: false,
            },
            description: {
                descriptionRegex: true,
                minlength: 10,
                required: true,
            },
            monday: {
                handle: true,
                required: false
            },
            tuesday: {
                handle: true,
                required: false
            },
            wednesday: {
                handle: true,
                required: false
            },
            thursday: {
                handle: true,
                required: false
            },
            friday: {
                handle: true,
                required: false
            },
            saturday: {
                handle: true,
                required: false
            },
            sunday: {
                handle: true,
                required: false
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

    $('#redirect-to-url').on('blur', function () {
        let curObj = $(this);
        let curVal = curObj.val();
        let https = "https://";
        if (curVal) {
            if (curVal.indexOf("http://") !== -1) { // if contains http: only, replace
                curObj.val(curVal.replace("http://", "https://"))
            } else if (curVal.indexOf("https://") === -1) { // if does not contain https:, prepend
                curObj.val(https + curVal);
            }
        }
    });


    $('.validate-create-service').validate({
        rules: {
            stripe_plan_name: {
                titleNameRegex: true,
                required: true
            },
            service_category: {
                digits: true, // uses a key value pair
                required: true,
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
                descriptionRegex: true,
                minlength: 10,
                required: true,
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
                minlength: 3,
                required: true,
            },
            description: {
                minlength: 10,
                required: true,
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
            event.preventDefault();
        }
    });

    $.validator.addMethod("descriptionRegex", function(value, element) {
        let valid = this.optional(element) || /^[a-zA-Z0-9\-_\s.,'"?:()$@!+=#]+$/i.test(value);
        if(!valid) {
            $(element).focus();
        }
        return valid;
    }, "Invalid character detected, please remove to continue");

    $.validator.addMethod("titleNameRegex", function(value, element) {
        let valid = this.optional(element) || /^[a-zA-Z0-9\-\s.,'"_()#]+$/i.test(value);
        if(!valid) {
            $(element).focus();
        }
        return valid;
    }, "Invalid character detected, please remove to continue");

    $.validator.addMethod("handle", function(value, element) {
        let valid = this.optional(element) || /^[a-zA-Z0-9\-_\s.]+$/i.test(value);
        if(!valid) {
            $(element).focus();
        }
        return valid;
    }, "field must contain only letters, numbers, periods or underscores.");

    $.validator.addMethod("password", function(value, element) {
        let valid = this.optional(element) || /^(?=.*[\d])(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*])[\w!@#$%^&*]{8,}$/i.test(value);
        if(!valid) {
            $(element).focus();
        }
        return valid;
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
        sendWarning('Max Upload size is 3MB only: <b class="text-danger">' + file.name + '<b>');
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
$('#choose-business-handle').on('input', function(){
    let val = $(this).val();
    $(this).val(val.replace(/[^\w]/gi, ''));
});
function checkHandleAvailability() {
    let handle = $('#choose-business-handle').val().trim();
    if(handle === '') {
        sendWarning("Please enter a value");
        $('.rest-of-biz-inputs').hide(500);
        return false;
    }
    let formData = $('form').serialize();
    $.post("/business/checkHandleAvailability", formData).done(function(data) {
        if(data === "1") {
            sendSuccess("This name is available! <br> Finish the form below <br><span class='text-danger'>If you do not rightfully own this name, it can be revoked and or modified</span>");
            $('#business-handle').val(handle);
            $('#chosen-handle').text("You chose @"+handle);
            $('.rest-of-biz-inputs').show(500);
            $('#business-handle').val(handle); // add this to the DB and ES index next
        } else {
            sendWarning("This name is not available<br><span class='text-danger'>If you rightfully own this business name, please contact <a href='/support' class='theme-color'>support</a> </span>");
            $('.rest-of-biz-inputs').hide(500);
        }
    });
    // sendSuccess("success");
    return true;
}


$('#list-filter').on('input', function () {
    let filterString = $(this).val(); // german/french word for this is zis
    $.expr[":"].contains = $.expr.createPseudo(function(arg) {
        return function( elem ) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });

    if(filterString.length > 1){
        $('.filterable').parents('.filterable-containter').addClass('hide');
        let matchedElements = $('.filterable:contains('+filterString+')');
        matchedElements.parents('.filterable-containter').removeClass('hide');
    } else {
        $('.filterable').parents('.filterable-containter').removeClass('hide');
    }

});
