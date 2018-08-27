<div id="plan-gallery-{{$plan->id}}" class="sm-modal autoscroll photo-upload-container" role="dialog">

    <div class="row" id="create-service-step1">
        <div class="col-md-8 offset-md-2">

            <div id="" class=" card" role="dialog">
                <!-- Modal content-->
                <div class="modal-content col-md-10 offset-md-1">
                    <div class="modal-header" ><h1></h1>
                        <p class="modal-title" style="width: 100%">Edit photos: {{$plan->stripe_plan_name}}<button class="btn-sm theme-background float-right hide-sm-modal">Done</button> </p>
                    </div>
                    <div class="modal-body">
                        <div class="plan-preview-photo">
                            <p class="text-center">*click photos to enlarge*</p>
                            <div class="text-center">Featured photo<br><p class="text-danger">*required*</p></div>
                            {{--FEATURED PHOTO START--}}
                            <div class="text-center p-3" data-id="{{$plan->id}}" style="border-radius: .3em;">
                                <p class="theme-color trigger-add-featured-photo hide">update</p>
                                <span class="fa fa-spinner fa-spin theme-color small-spinner"></span>
                                <span class="fa fa-check theme-color check-mark"></span><br>
                                <span class="fa fa-photo fa-2x placeholder" id="trigger-add-featured-photo" data-target="#featured-photo-{{$plan->id}}" style="display: {{!empty($plan->featured_photo_path) ? 'none' : ''}}"></span>
                                <img src="{{!empty($plan->featured_photo_path) ? getImage($plan->featured_photo_path) : ''}}" id="featured-photo-temp-{{$plan->id}}" class="featured-photo-temp-{{$plan->id}}" width="40" data-lity>
                                <br>
                                <span class="fa fa-close remove text-danger remove-featured-photo {{!empty($plan->featured_photo_path) ? '' : 'hide'}}" data-target="#featured-photo-temp" onclick="clearImage(this)">
                                    <span class="theme-color"></span>
                                </span>
                            </div>

                            <hr>
                            {{--FEATURED PHOTO END--}}
                            <div class="col-md-8 offset-md-2 p-0">
                                <p class="text-center">Gallery photos<br>up to 4</p>
                                <div class="row">
                                    @for($i=0; $i < 4; $i++)
                                        @php $hasPhoto = isset($plan->photos[$i]); @endphp
                                        <div class="col-3 text-center gallery-photo-container-{{$plan->id}}-{{ $hasPhoto ? 'queued' : 'empty' }}" data-id="{{$plan->id}}">
                                            <span class="fa fa-spinner fa-spin theme-color small-spinner"></span>
                                            <span class="fa fa-check theme-color check-mark"></span><br>
                                            <span class="fa fa-2x fa-photo placeholder trigger-add-gallery-photos" data-target="#gallery-photos-{{$plan->id}}" style="display: {{ $hasPhoto ? 'none' : ''}}"></span>
                                            <img src="{{$hasPhoto ? getImage($plan->photos[$i]->path) : ''}}" id="gallery-photo-temp-{{$i}}" width="40" data-lity>
                                            <br>
                                            <span class="fa fa-close remove text-danger remove-gallery-photo" data-target="#gallery-photo-temp-{{$i}}" onclick="clearImage(this, true)" style="display: {{isset($plan->photos[$i]) ? '' : 'none'}}"></span>
                                            <form class="hide" method="post" action="{{ $hasPhoto ? "/plan/galleryPhoto/".$plan->photos[$i]->id : ''}}">
                                                {{csrf_field()}}
                                                {{method_field("delete")}}
                                            </form>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <form method="post" action="/plan/featuredPhoto/{{$plan->id}}" class="featured-photo-form text-center hide featured-photo-dz" id="plan-dropzone-{{$plan->id}}">
        <input type="file" name="featured_photo" id="featured-photo-{{$plan->id}}" onchange="readFeaturedImg(this, true, {{$plan->id}})" style="visibility: hidden">
        {{csrf_field()}}
        {{method_field("post")}}
    </form>

    <form class="gallery-photos-form hide" id="gallery-dropzone-{{$plan->id}}" method="post" action="/plan/galleryPhoto/{{$plan->id}}">
        <input type="file" name="gallery_photos" id="gallery-photos-{{$plan->id}}" data-image-container="gallery-photo-container-{{$plan->id}}-" onchange="readImages(this, true)" style="visibility: hidden" multiple>
        {{csrf_field()}}
        {{method_field("post")}}
    </form>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</div>

@section('footer')
    <script src="{{ baseUrlConcat('/js/create-service.js') }}"></script>
@endsection