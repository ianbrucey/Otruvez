/**
 * Created by macbook on 11/9/17.
 */
$('.show-sm-modal').on('click', function(){
    var target = $(this).attr('data-modal-target');
    $(target).fadeIn(500);
    console.log(target);
});

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

$('.hide-sm-modal').on('click', function(){
    $('.sm-modal').hide(500);
});

$('.sm-modal').on('click', function(e){
    if (e.target !== this){
        return;
    }
    $(this).hide(500);
});

$('.has-business-hours').on('change', function(){
    if($(this).is(':checked'))
    {
        $('.business-hours').show();
    } else {
        $('.business-hours').hide();
    }
});
//
// $('#review-form').submit(function (event) {
//     event.preventDefault();
//     var reviewContainer = $('#review-container');
//     var userName = $(this).children('.user-name').val();
//     var reviewBody = $(this).children('.review-body').val();
//     var date = $(this).children('.date').val();
//     var review = $('<div class="review"><p>'+reviewBody+'</p><small class="text-muted">Posted by <b>'+userName+'</b> on '+date+'</small></div><hr>');
//     reviewContainer.prepend(review);
// });


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

function triggerTargetSubmit(e, obj, ajaxSubmit = false) {
    e = e || window.event;
    e.preventDefault();
    let form = $($(obj).attr('data-target'));
    let paginationIndex = $(obj).attr('data-from');
    if(paginationIndex > 0) {
        form.append('<input type="hidden" name="from" value="'+paginationIndex+'">');
    }
    if(ajaxSubmit) {
        if(form.find('#subject').val() == '' || form.find('#body').val() == ''){
            $.confirm({
                icon: 'fa fa-warning text-danger',
                title: '',
                content: 'both fields are required',
                buttons: {
                    ok: {
                        btnClass: 'theme-background',
                    }
                }
            });
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
                    triggerTargetSubmit(e, obj);
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
                    triggerTargetSubmit(e, obj);
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

    $('.validate-message').validate({
        rules: {
            subject: {
                required: true,
                minlength: 3
            },
            body: {
                required: true,
                minlength: 10
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
                minlength: 3
            },
            email: {
                required: true,
                email: true,
                minlength: 10
            },
            phone: {
                required: true,
                minlength: 10
            },
            description: {
                required: true,
                minlength: 10
            },
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

    $('.validate-login').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 8
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

    $('.validate-register').validate({
        rules: {
            first: {
                required: true
            },
            last: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 8
            },
            password_confirmation: {
                required: true,
                minlength: 8
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

});

// may keep for smoother transition
$( window ).ready(function() {
    // $('#blanket').show();
    $('#blanket').fadeOut(500);
});
// When clicking here, we will trigger the dropzone that
// lets us choose a NEW FEATURED PHOTO for the the PLAN


