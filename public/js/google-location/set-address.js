/**
 * Created by macbook on 11/9/17.
 */
// alert('set address');
var placeSearch, autocomplete;
var componentForm = {
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
};

function initAutocomplete() {
    // Create the autocomplete object, restricting the search to geographical
    // location types.
    // console.log("initing autocomplete...");
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
        {types: ['geocode']});

    // When the user selects an address from the dropdown, populate the address
    // fields in the form.
    autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
    // Get the place details from the autocomplete object.
    // console.log("filling address...");
    let place = autocomplete.getPlace();
    let addressLabel = $(".address-selected-label");
    let city = '';
    let state = '';
    const g_city_type = "locality";
    const g_state_type = "administrative_area_level_1";

    for (let component in componentForm) {
        document.getElementById(component).value = '';
        document.getElementById(component).disabled = false;
    }

    // Get each component of the address from the place details
    // and fill the corresponding field on the form.
    for (let i = 0; i < place.address_components.length; i++) {
        let addressType = place.address_components[i].types[0];
        let val = null;
        if (componentForm[addressType]) {
            let val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
            console.log(val);
            if(addressType === g_city_type) {
                city = val;
            } else if(addressType === g_state_type) {
                state = val;
            }
        }


        addressLabel.text("You've selected: " + city + ", " + state);
    }

    $('#lat').val(place.geometry.location.lat());
    $('#lng').val(place.geometry.location.lng());
    $('#address').val($('#autocomplete').val());
}

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
    console.log("geolocating...");
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
        });
    }
}


$('#autocomplete').parents('form').on('submit', function(e){
    if(e.keyCode === 13) {
        event.preventDefault();
        return false;
    }
});

$(document).ready(function () {
    setTimeout(function () {
        $('#autocomplete').attr('autocomplete', 'nah');
    }, 1000);
});