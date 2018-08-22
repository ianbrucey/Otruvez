/**
 * Created by macbook on 11/9/17.
 */

let currentLocation = window.location.href;
let dropzones = $('.dropzone');
let acceptedFileTypes = ".png,.jpeg,.jpg,.gif";
let successful = false;
let loadingPhoto = $('#loading-photo');
Dropzone.autoDiscover = false;

$(function() {
    // Now that the DOM is fully loaded, create the dropzone, and setup the
    // event listeners;


    let featuredDZ = new Dropzone(".featured-photo-form", {
        paramName: "featured_photo",
        acceptedFiles: acceptedFileTypes,
        maxFiles: 1,
        maxFilesize: 1,
        uploadMultiple: false,
        init: function () {

        },
        complete: function (file) {
            if(successful) {
                // window.location.href = currentLocation + "?uploadSuccess";
            }
            let reader = new FileReader();
            reader.onload = function() {
                console.log(reader.result);
            };

            reader.readAsDataURL(file);
        },
        sending: function (file) {
            loadingPhoto.show(500);
            console.log(file);
        },
        success: function () {
            successful = true;
            console.log("success");
        },
        error: function (file, err, xhr) {
            loadingPhoto.fadeOut();
            successful = false;
            sendWarning("error");
        }
    });

    let GalleryDZ  = new Dropzone(".gallery-photos-form", {
        paramName: "gallery_photos[]",
        maxFiles: 4,
        acceptedFiles: acceptedFileTypes,
        uploadMultiple: true,
        init: function () {
            console.log("gall");
        },
        complete: function () {
            // loadingPhoto.fadeOut();
            // window.location.href = currentLocation + "?uploadSuccess";
            if(successful) {
                console.log("complete");
            } else {
                console.log("not complete");
            }
        },
        sending: function () {
            // loadingPhoto.fadeIn();
        },
        success: function () {
            successful = true;
            console.log("success");
        },
        error: function (file, err, xhr) {
            // console.log(xhr);
            successful = false;
            console.log("error");
        }
    });


});