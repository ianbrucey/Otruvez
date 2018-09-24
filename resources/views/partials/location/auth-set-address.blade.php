<div class="address-container pt-2">
    <p class="address-selected-label text-white">no new address selected</p>
    <input id="autocomplete" placeholder="Enter your address"
           onFocus="geolocate()" class="form-control" type="text" autocomplete="new-address" value="{{$business->address}}">
    <input type="hidden" class="field" id="address" name="address" value="{{$business->address}}">
    <input type="hidden" class="field" id="locality" name="city" value="{{$business->city}}">
    <input type="hidden" class="field" id="administrative_area_level_1" name="state" value="{{$business->state}}">
    <input type="hidden" class="field" id="postal_code" name="zip" value="{{$business->zip}}">
    <input type="hidden" class="field" id="country" name="country" value="{{$business->country}}">
    <input type="hidden" class="field" id="lat" name="lat" value="{{$business->lat}}">
    <input type="hidden" class="field" id="lng" name="lng" value="{{$business->lng}}">
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
