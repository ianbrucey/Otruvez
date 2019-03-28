<div class="row page-search">
    <!-- Store Search -->
    <form action="/home/findServices" method="get" class="col-md-5 offset-md-1" id="search-form" autocomplete="off">

        <div class="block d-flex">
            <input type="hidden" name="submitted"  value=true>
            <input autocomplete="false" name="hidden" type="text" style="display:none;">
            <input type="hidden" class="field" id="address" name="address">
            <input type="hidden" class="field" id="locality" name="city" value="{{$location->city}}">
            <input type="hidden" class="field" id="administrative_area_level_1" name="state" value="{{$location->state}}">
            <input type="hidden" class="field" id="postal_code" name="postal" value="{{$location->postal}}">
            <input type="hidden" class="field" id="country" name="country_name" value="{{$location->country_name}}">
            <input type="hidden" class="field" id="lat" name="latitude" value="{{$location->latitude}}">
            <input type="hidden" class="field" id="lng" name="longitude" value="{{$location->longitude}}">
            <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" value="{{$searchField ?: ''}}" id="searchField" name="searchField" placeholder="Search for a service, product or business name" style="background: white " required>
        </div>
        <hr>
        <a class="text-white" data-toggle="collapse" data-target="#more-criteria" aria-expanded="false" aria-controls="more-criteria">
            <span class="fa fa-plus-circle"></span> Advanced search
        </a>
        <div class="block form-group collapse" id="more-criteria">
            <label class="text-white">Category</label>
            <select class="form-control bg-white" name="category" id="category">
                @foreach(SERVICE_CATEGORY_LIST as $key => $value)
                    <option value="{{$key}}" {{$selected = isset($category) && $category == $key ? 'selected' : ''}}>{{$value}}</option>
                @endforeach
            </select>
            <label class="text-white">Distance in miles:</label>
            <input id="miles" name="miles" type="number" value="{{$miles}}" class="form-control bg-white" min="1" max="10000" placeholder="Distance in miles">
        </div>
        <hr>
    </form>

    <div class="col-md-3 col-sm-12" id="location-form">
        <div class="block d-flex location-label-container">
            <input id="autocomplete" placeholder="Enter your city"
                   onFocus="geolocate()" class="form-control" type="text" style="background: white" value="{{$location->city}}, {{strtoupper(substr($location->state,0,2))}}" autocomplete="offsakdjhfjkads fhsd fk">
        </div>

        <div id="location-list-container" class="col-md-8 card">
            <ul style="list-style: none;" id="location-list">
                <li class="card-header">Loading...</li>
            </ul>
        </div>

    </div>
    <div class="col-md-2 col-sm-12">
        <div class="block d-flex">
            <button class="btn btn-default form-control" style="background: white" onclick="triggerTargetSubmit(event, this, false)" data-target="#search-form" id="searchField-btn" disabled><span class="fa fa-search"></span></button>
        </div>
    </div>
</div>