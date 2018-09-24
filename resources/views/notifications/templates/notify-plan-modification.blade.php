<h4>Please see the changes to your subscription below</h4>
<hr>
<h5 class="text-danger">Old Details</h5>
<ul style="width: 100%">
    <li>Name: <h3 class="text-muted">{{$oldName}}</h3></li>

    <li>Description: <p>{{$oldDescription}}</p></li>
</ul>
<hr class="theme-color">
<h5 class="theme-color">New Details</h5>
<ul style="width: 100%">
    <li>Name: <h3 class="theme-color">{{$newName}}</h3></li>

    <li>Description: <p class="theme-color">{{$newDescription}}</p></li>
</ul>
<hr>
<div class="row text-center">
    @if($logoPath)
        <div class="col-12">
            <h3 class="text-center m-3">{{$companyName}} thanks you</h3>
        </div>
        <div style="margin: auto; margin-bottom: 5px; width: 70px; height: 70px; background: url({{ getImage($logoPath) }}) no-repeat; background-size: contain"> </div>
    @endif
</div>