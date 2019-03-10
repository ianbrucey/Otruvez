<div class="address-container pt-2">
    <h4 class="text-white address-label"><u>Address</u></h4>
    <p class="address-selected-label text-white">{{old('full_address') ? sprintf("You chose, %s, %s",old('city'),old('state')) : "no address selected"}}</p>
    <input id="autocomplete" placeholder="Enter your address"
           onFocus="geolocate()" class="form-control" type="text" name="full_address" autocomplete="offsakdjhfjkads fhsd fk" value="{{old('full_address')}}">
    <input type="hidden" class="field" id="address" name="address" value="{{old('address')}}">
    <input type="hidden" class="field" id="locality" name="city" value="{{old('city')}}">
    <input type="hidden" class="field" id="administrative_area_level_1" name="state" value="{{old('state')}}">
    <input type="hidden" class="field" id="postal_code" name="zip" value="{{old('zip')}}">
    <input type="hidden" class="field" id="country" name="country" value="{{old('country')}}">
    <input type="hidden" class="field" id="lat" name="lat" value="{{!empty(old('lat')) ? old('full_address') : "0"}}">
    <input type="hidden" class="field" id="lng" name="lng" value="{{!empty(old('lng')) ? old('full_address') : "0"}}">
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
