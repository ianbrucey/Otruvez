<div class="address-container pt-2">
    <h4 class="text-white address-label"><u>Address</u></h4>
    <p class="address-selected-label text-white">no address selected</p>
    <input id="autocomplete" placeholder="Enter your address"
           onFocus="geolocate()" class="form-control" type="text" autocomplete="offsakdjhfjkads fhsd fk">
    <input type="hidden" class="field" id="address" name="address">
    <input type="hidden" class="field" id="locality" name="city">
    <input type="hidden" class="field" id="administrative_area_level_1" name="state">
    <input type="hidden" class="field" id="postal_code" name="zip">
    <input type="hidden" class="field" id="country" name="country">
    <input type="hidden" class="field" id="lat" name="lat" value="0">
    <input type="hidden" class="field" id="lng" name="lng" value="0">
</div>
<script>
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
</script>
<style>
    .pac-container {
        z-index: 99999999 !important;
    }
</style>
