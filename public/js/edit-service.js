$('.trigger-add-gallery-photos, #trigger-add-featured-photo, .trigger-add-featured-photo').click(function(e){
    let targetId        = $(this).attr('data-target');
    let targetElement   = $(targetId);
    targetElement.trigger('click');
});

let fileArray = {};

let uploadContainer = $('.photo-upload-container');
let deleteAction    = "/plan/galleryPhoto/";

function readFeaturedImg(input, async = null, planId) {

    if(input.files[0]) {
        let reader = new FileReader();
        reader.onload = function (e) {
            let res         = reader.result;
            let img         = $('#featured-photo-temp-' + planId);
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
                parent.children('.small-spinner').css('display','inline-block');
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
    let imageContainer = $(input).attr("data-image-container");
    let queuedClass = imageContainer + 'queued';
    let emptyClass  = imageContainer + 'empty';
    let queued      = $('.'+queuedClass);
    let empty       = $('.'+emptyClass);
    let uploadLimit = queued.length === 0 ? 4 : 4 - queued.length;
    let backend     = {success: false, msg: ''};

    for(let t = 0; t < uploadLimit; t++) {
        if(!input.files[t]) {
            continue;
        }
        let reader = new FileReader();
        reader.onload = function (e) {
            let res         = reader.result;
            let currElement = empty.eq(t).children('img'); // gotta look here to fix the bug
            let parent      = currElement.parent('div');
            let placeHolder = parent.children('.placeholder');
            let clearImgBtn = parent.children('.remove');
            let deleteForm  = parent.children('form');
            currElement.attr('src', res).width(30);

            parent.removeClass(emptyClass).addClass(queuedClass);
            fileArray[currElement.attr('id')] = res;
            clearImgBtn.show();
            placeHolder.hide();

            if(async) {
                let form        = $(input).parent('form');
                let url         = form.attr('action');
                let postdata = new FormData(form[0]);
                postdata.append("gallery_photos", input.files[t]);
                parent.find('.small-spinner').css('display','inline-block');
                ajaxPost(url, postdata, clearImgBtn.get(0), backend).done(function (data) {
                    deleteForm.attr('action', data.deleteRoute);
                    parent.find('.small-spinner').hide();
                    parent.find('.check-mark').show(500);
                });

            }
            uploadContainer.addClass("refresh");
        };

        reader.readAsDataURL(input.files[t]);


    }



}

function clearImage(input, async = false) {
    let dis         = $(input);
    let parent      = dis.parent('div');
    let planId      = parent.attr('data-id');
    let img         = parent.children('img');
    let deleteForm  = parent.children('form');
    let placeHolder = parent.children('.placeholder');
    let clearImgBtn = parent.children('.remove');
    let imgContainerPrefix = 'gallery-photo-container-' + planId;
    let queuedClass = imgContainerPrefix + '-queued';
    let emptyClass  = imgContainerPrefix + '-empty';
    img.attr('src','');



    if(dis.hasClass('remove-featured-photo')) {
        let fpFormInput = document.getElementById('featured-photo-' + planId);
        fpFormInput.value = '';
        if(fpFormInput.value){
            fpFormInput.type = "text";
            fpFormInput.type = "file";
        }
    } else if(dis.hasClass('remove-gallery-photo')) {
        let gpFormInput = document.getElementById('gallery-photos-' + planId);
        gpFormInput.value = '';
        if(gpFormInput.value){
            gpFormInput.type = "text";
            gpFormInput.type = "file";
        }
    }
    if(!img.hasClass('featured-photo-temp-' + planId)) {
        parent.removeClass(queuedClass).addClass(emptyClass);
    } else {
        $('.create-service-next-step').prop('disabled', true);
    }
    fileArray[img.attr('id')] = null;
    placeHolder.show();
    clearImgBtn.hide();
    parent.children('.fa-check').hide();


    if(async) {
        console.log("we here");
        parent.find('.small-spinner').css('display','inline-block');

        let postdata = new FormData(deleteForm[0]);
        ajaxPost(deleteForm.attr('action'), postdata, null, true).done(function (data) {
            // sendSuccess("Photo deleted")
        });
    }
}

function ajaxPost(route, formDataObj, clearImageObj, backend = null) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    return $.ajax({
        url: route,
        type: formDataObj.get('_method'),
        data: formDataObj,
        async: true,
        success: function (data, status, xhr) {
            loadingPhoto.hide();
            $('.small-spinner').hide();
        },
        error: function (xhr, status, msg) {
            backend.success = false;
            backend.msg     = xhr.responseJSON.msg;
            loadingPhoto.hide();
            clearImage(clearImageObj);
            sendWarning(xhr.responseJSON.msg);
            $('.small-spinner').hide();
        },
        cache: false,
        contentType: false,
        processData: false
    });
}