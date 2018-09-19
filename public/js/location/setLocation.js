const locationURI       = "/location";
const locationContainer = $('#location-list-container');
const locationList      = $('#location-list');
const locationForm      = $('#location-form');
const locationInput      = $('#location');
const locationLabel      = $('#location-label');
const searchingIn       = $('#searching-in');
const updateLocation = $('#update-location');
const locationId       = $('#location_id');


$('#location').on("keyup",function()
{
    let strLen = $(this).val().length;
    let locationForm = $('#location-form').serialize();

    if(strLen > 1){
        locationContainer.show();
        $.get(locationURI,locationForm).done(function(response){
            locationList.html(response);
        });
    } else {
        locationContainer.hide();
    }
});


// Handle the selection of a location
$(document).on('click','.location-item', function(){
    locationContainer.hide();
    locationInput.hide();
    locationLabel.html('<span class="fa fa-location-arrow fa-2x"></span>' + $(this).attr("data-city") + ", " + $(this).attr("data-state"));
    locationLabel.show()
    locationId.val($(this).attr("data-id"));
});

updateLocation.on('click', function(){
    locationForm.removeClass("hide");
    $(this).hide();
    console.log('showing');
});

locationLabel.on('click', function(){
    $(this).hide();
    locationInput.removeClass("hide").show().val("").focus();
});

// todo: fix the csrf issue
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function ajaxLocation() {
    $.ajax({
        url: "https://geoip-db.com/jsonp",
        jsonpCallback: "callback",
        dataType: "jsonp",
        success: function( location ) {
            $('#country').html(location.country_name);
            $('#state').html(location.state);
            $('#city').html(location.city);
            $('#latitude').html(location.latitude);
            $('#longitude').html(location.longitude);
            $('#ip').html(location.IPv4);
        }
    });
}