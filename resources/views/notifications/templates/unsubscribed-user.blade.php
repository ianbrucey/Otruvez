<h4>You've canceled your subscription to <span class="theme-color">{{$companyName}}</span>.</h4>
<hr>
<ul style="width: 100%">
    <li>Subscription name: <h3>{{$serviceName}}</h3></li>

    <li>Confirmation ID: <p style="color: black">{{$confirmationId}}</p></li>

</ul>

<hr>
<div class="row text-center">
    @if($logoPath)
        <div class="col-12">
            <h3 class="text-center m-3">{{$companyName}} thanks you</h3>
        </div>
        <div style="margin: auto; margin-bottom: 5px; width: 200px; height: 200px; background: url({{ getImage($logoPath) }}) no-repeat; background-size: contain"> </div>
    @endif
</div>