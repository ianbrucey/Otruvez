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
                            <div class="text-center p-3"  style="border-radius: .3em;">
                                <p class="theme-color trigger-add-featured-photo hide">update</p>
                                <span class="fa fa-photo fa-2x placeholder" id="trigger-add-featured-photo" style="display: {{!empty($plan->featured_photo_path) ? 'none' : ''}}"></span>
                                <img src="{{!empty($plan->featured_photo_path) ? getImage($plan->featured_photo_path) : ''}}" id="featured-photo-temp" class="featured-photo-temp" width="40" data-lity>
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
                                        <div class="col-3 text-center empty ">
                                            <span class="fa fa-check theme-color check-mark" style="display: none"></span><br>
                                            <span class="fa fa-2x fa-photo placeholder trigger-add-gallery-photos" style="display: {{isset($plan->photos[$i]) ? 'none' : ''}}"></span>
                                            <img src="{{isset($plan->photos[$i]) ? getImage($plan->photos[$i]->path) : ''}}" id="gallery-photo-temp-{{$i}}" width="40" data-lity>
                                            <br>
                                            <span class="fa fa-close remove text-danger remove-gallery-photo" data-target="#gallery-photo-temp-{{$i}}" onclick="clearImage(this)" style="display: {{isset($plan->photos[$i]) ? '' : 'none'}}"></span>
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

    <form method="POST" action="/plan/featuredPhoto/{{$plan->id}}" class="featured-photo-form text-center hide featured-photo-dz" id="plan-dropzone-{{$plan->id}}">
        <input type="file" name="featured_photo" id="featured-photo" onchange="readFeaturedImg(this, true)" style="visibility: hidden">
        {{csrf_field()}}
    </form>

    <form class="gallery-photos-form hide" id="gallery-dropzone-{{$plan->id}}" method="POST" action="/plan/galleryPhoto/{{$plan->id}}">
        <input type="file" name="gallery_photos" id="gallery-photos" onchange="readImages(this, true)" style="visibility: hidden" multiple>
        {{csrf_field()}}
    </form>

</div>

@section('footer')
    <script src="{{ baseUrlConcat('/js/create-service.js') }}"></script>
@endsection