$('#trigger-add-featured-photo').click(function(e){
    $('#featured-photo').trigger('click');
});

$('.trigger-add-gallery-photos').click(function(e){
    $('#gallery-photos').trigger('click');
});

let fileArray = {};

function readFeaturedImg(input, async = null) {
    console.log("got it");
    if(input.files[0]) {
        let reader = new FileReader();
        reader.onload = function (e) {
            let res         = reader.result;
            let img         = $('#featured-photo-temp');
            let parent      = img.parent('div');
            let placeHolder = parent.children('.placeholder');
            let clearImgBtn = parent.children('.remove');
            img.attr('src', res).width(30);

            fileArray[img.attr('id')] = res;
            clearImgBtn.show();
            placeHolder.hide();

            if(async) {
                let form        = $('.featured-photo-form');
                let url         = form.attr('action');
                let postdata    = form.serialize();
                $.post(url, postdata)
                    .done(function (msg) {
                        sendSuccess(msg);
                    }).fail(function (msg) {
                    clearImage(input);
                    sendWarning(msg);
                });
            }
        };

        reader.readAsDataURL(input.files[0]);

        $('.create-service-next-step').prop('disabled', false);
    }
}

function readImages(input, async = null) {

    let queued = $('.queued');
    let empty  = $('.empty');
    let uploadLimit = queued.length === 0 ? 4 : 4 - queued.length;

    for(let t = 0; t < uploadLimit; t++) {
        let reader = new FileReader();
        reader.onload = function (e) {
            let res         = reader.result;
            let currElement = empty.eq(t).children('img');
            let parent      = currElement.parent('div');
            let placeHolder = parent.children('.placeholder');
            let clearImgBtn = parent.children('.remove');
            currElement
                .attr('src', res)
                .width(30);

            parent.removeClass('empty').addClass('queued');
            fileArray[currElement.attr('id')] = res;
            clearImgBtn.show();
            placeHolder.hide();

            if(async) {
                let form        = $('.gallery-photos-form');
                let url         = form.attr('action');
                let postdata    = form.serialize();
                $.post(url, postdata)
                  .done(function (data) {
                    sendSuccess(data);
                }).fail(function (data) {
                    clearImage(input);
                    sendWarning(data);
                });
            }
        };

        reader.readAsDataURL(input.files[t]);


    }

}

function clearImage(input, async = null) {
    let imgId       = $(input).attr('data-target');
    let img         = $(imgId);
    let parent      = img.parent('div');
    let placeHolder = parent.children('.placeholder');
    let clearImgBtn = parent.children('.remove');
    if(!img.hasClass('featured-photo-temp')) {
        parent.removeClass('queued').addClass('empty');
    } else {
        $('.create-service-next-step').prop('disabled', true);
    }
    img.attr('src', '');
    fileArray[img.attr('id')] = null;
    placeHolder.show();
    clearImgBtn.hide();
    console.log(fileArray);

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