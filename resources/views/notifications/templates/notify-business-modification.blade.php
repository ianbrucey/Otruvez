<h4>We thought you should know that we changed some of the details for our business. Your subscriptions will not be affected.</h4>
<h5>The details are below</h5>

<hr>
<div class="card">
    <div class="card-body">
        <h3 class="text-justify">{{$business->name}}</h3>
        <p><i>"{{$business->description}}"</i></p>
        <p>{{$business->email}}</p>
        <p>{{$business->phone}}</p>
        <p>{{$business->address}}</p>
        <h5><b><u>Business hours</u></b></h5>
        <div class="business-hours" style="display: block">
            @foreach($days as $day)
                <div class="edit-label-div">
                    <label>{{ucfirst($day)}}</label>
                </div>
                <div class="edit-input-div">
                    <p>{{$business->$day}}</p>
                </div>

            @endforeach
        </div>
    </div>
</div>
<hr>
<div class="row text-center">
    @if($business->logo_path)
        <div class="col-12">
            <h3 class="text-center m-3">{{$business->name}} thanks you</h3>
        </div>
        <div style="margin: auto; margin-bottom: 5px; width: 70px; height: 70px; background: url({{ getImage($business->logo_path) }}) no-repeat; background-size: contain"> </div>
    @endif
</div>
