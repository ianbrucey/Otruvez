
<div class="col-12">
    <h2 class="text-center theme-color">
        <b>
        @if($business->logo_path)
            <div class="d-inline-block" style="width: 200px; height: 100px; background: url({{ getImage($business->logo_path) }}) no-repeat; background-size: contain; background-position: center;" ></div>
    @else
        {{$business->name}}

    @endif
        </b>
    </h2>
</div>

<div class="container">
    <div class="row">
        <a class="col-4 btn-dark text-center p-2 theme-background {{$active == 'home' ? 'active' : ''}}" href="/business/viewStore/{{$business->id}}"> store </a>
        <a class="col-4 btn-dark text-center p-2 theme-background {{$active == 'about' ? 'active' : ''}}" href="/business/viewStore/{{$business->id}}/about"> about </a>
        <a class="col-4 btn-dark text-center p-2 theme-background {{$active == 'contact' ? 'active' : ''}}" href="/business/viewStore/{{$business->id}}/contact"> contact </a>
    </div>
</div>

