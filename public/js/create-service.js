$('#trigger-add-featured-photo, .trigger-add-featured-photo').click(function(e){
    $('#featured-photo').trigger('click');
});

$('.trigger-add-gallery-photos').click(function(e){
    $('#gallery-photos').trigger('click');
});

let fileArray = {};

let uploadContainer = $('.photo-upload-container');

function readFeaturedImg(input, async = null) {

    if(input.files[0]) {
        console.log("got it");
        let reader = new FileReader();
        reader.onload = function (e) {
            let res         = reader.result;
            let img         = $('#featured-photo-temp');
            let parent      = img.parent('div');
            let placeHolder = parent.children('.placeholder');
            let clearImgBtn = parent.children('.remove');
            if(!async) {
                img.attr('src', res).width(30);
            }

            fileArray[img.attr('id')] = res;
            clearImgBtn.show();
            placeHolder.hide();

            if(async) {
                let form        = $(input).parent('form');
                let url         = form.attr('action');
                let postdata = new FormData(form[0]);

                ajaxPost(url, postdata, clearImgBtn.get(0)).done(function () {
                    img.attr('src', res).width(30);
                    parent.children('.fa-check').show(1000);
                });


            }

            uploadContainer.addClass("refresh");
        };

        reader.readAsDataURL(input.files[0]);

        $('.create-service-next-step').prop('disabled', false);
    } else {
        console.log("nope");
    }
}

function readImages(input, async = null) {

    let queued      = $('.queued');
    let empty       = $('.empty');
    let uploadLimit = queued.length === 0 ? 4 : 4 - queued.length;
    let backend     = {success: true, msg: ''};

    for(let t = 0; t < uploadLimit; t++) {
        let reader = new FileReader();
        reader.onload = function (e) {
            let res         = reader.result;
            let currElement = empty.eq(t).children('img');
            let parent      = currElement.parent('div');
            let placeHolder = parent.children('.placeholder');
            let clearImgBtn = parent.children('.remove');
            // currElement
            //     .attr('src', res)
            //     .width(30);

            parent.removeClass('empty').addClass('queued');
            fileArray[currElement.attr('id')] = res;
            clearImgBtn.show();
            placeHolder.hide();

            let form        = $(input).parent('form');
            let url         = form.attr('action');
            let postdata = new FormData(form[0]);

            ajaxPost(url, postdata, clearImgBtn.get(0), backend).done(function (data) {
                currElement.attr('src', data.path).width(30);
                parent.find('.check-mark').show(500);
            });

            uploadContainer.addClass("refresh");
        };

        reader.readAsDataURL(input.files[t]);


    }



}

function clearImage(input, async = null) {
    let dis         = $(input);
    let imgId       = dis.attr('data-target');
    let img         = $(imgId);
    let parent      = img.parent('div');
    let placeHolder = parent.children('.placeholder');
    let clearImgBtn = parent.children('.remove');
    if(dis.hasClass('remove-featured-photo')) {
        let fpFormInput = document.getElementById('featured-photo');
        fpFormInput.value = '';
        if(fpFormInput.value){
            fpFormInput.type = "text";
            fpFormInput.type = "file";
        }
    } else if(dis.hasClass('remove-gallery-photo')) {
        let gpFormInput = document.getElementById('gallery-photos');
        gpFormInput.value = '';
        if(gpFormInput.value){
            gpFormInput.type = "text";
            gpFormInput.type = "file";
        }
    }
    if(!img.hasClass('featured-photo-temp')) {
        parent.removeClass('queued').addClass('empty');
    } else {
        $('.create-service-next-step').prop('disabled', true);
    }
    img.attr('src', '');
    fileArray[img.attr('id')] = null;
    placeHolder.show();
    clearImgBtn.hide();
    parent.children('.fa-check').hide(1000);


    if(async) {
        let targetForm  = $(input).attr('target-form');
        let postdata    = targetForm.serialize();
        let url         = targetForm.attr('action');
        $.post(url, postdata).done(function (data) {
            sendSuccess(data);
        }).fail(function (data) {
            sendWarning(data);
        });
    }
}

let step1 = $('#create-service-step1');
let step2 = $('#create-service-step2');
$('.create-service-next-step').on('click', function () {
    step1.fadeOut(500);
    step2.fadeIn(700);
});

$('.create-service-previous-step').on('click', function () {
    step1.fadeIn(700);
    step2.fadeOut(500);
});

function ajaxPost(route, formDataObj, clearImageObj, backend = null) {
    return $.ajax({
        url: route,
        type: 'POST',
        data: formDataObj,
        async: true,
        success: function (data, status, xhr) {
            loadingPhoto.hide();
            if(!backend) {
                sendSuccess(xhr.responseJSON.msg);
            }
        },
        error: function (xhr, status, msg) {
            backend.success = false;
            backend.msg     = xhr.responseJSON.msg;
            loadingPhoto.hide();
            clearImage(clearImageObj);
            sendWarning(xhr.responseJSON.msg);
        },
        cache: false,
        contentType: false,
        processData: false
    });
}