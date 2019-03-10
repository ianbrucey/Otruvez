/**
 * Created by macbook on 11/9/17.
 */

let dropzones = $('.dropzone');
let acceptedFileTypes = ".png,.jpeg,.jpg,.gif";
let successful = false;
Dropzone.autoDiscover = false;

$(function() {
    // Now that the DOM is fully loaded, create the dropzone, and setup the
    // event listeners;



    if($('#business-logo-dropzone').length > 0) {
        let GalleryDZ  = new Dropzone("#business-logo-dropzone", {
            paramName: "file",
            maxFiles: 1,
            acceptedFiles: acceptedFileTypes,
            uploadMultiple: false,
            init: function () {
                console.log("logo");
            },
            complete: function () {
                // loadingPhoto.fadeOut();

                if(successful) {
                    window.location.href = currentLocation + "?uploadSuccess";
                } else {
                    window.location.href = currentLocation + "?uploadFailed";
                }
            },
            sending: function () {
                loadingPhoto.fadeIn();
            },
            success: function () {
                successful = true;
                console.log("success");
            },
            error: function (file, err, xhr) {
                // console.log(xhr);
                successful = false;
                console.log("error");
                loadingPhoto.fadeOut();
            }
        });
    }


});