{{--<div id="plan-gallery-{{$plan->id}}" class="sm-modal autoscroll" role="dialog" style="display: block">--}}
    {{--<!-- Modal content-->--}}
    {{--<div class="modal-content col-md-8 offset-md-2">--}}
        {{--<div class="modal-header">--}}
            {{--<button type="button" class="hide-sm-modal float-left" data-dismiss="modal">&times;</button>--}}
            {{--<h4 class="modal-title">Edit Photos</h4>--}}
        {{--</div>--}}
        {{--<div class="modal-body">--}}
            {{--<div class="plan-preview-photo">--}}
                {{--<div class="text-center">Featured photo</div>--}}
                {{--FEATURED PHOTO START--}}
                {{--@php $hasFeaturedPhoto = !empty($plan->featured_photo_path); @endphp--}}
                {{--<div class="text-center p-2">--}}
                    {{--<a class="btn-sm theme-background" data-target="#plan-dropzone-{{$plan->id}}" onclick="triggerTargetClick(event, this)">update</a>--}}
                    {{--<form method="POST" action="/plan/featuredPhoto/{{$plan->id}}" class="featured-photo-form text-center hide featured-photo-dz" id="plan-dropzone-{{$plan->id}}" >--}}
                        {{--<span data-dz-message class="fa fa-photo fa-1x dz-message"><br>Add a featured image</span>--}}
                        {{--{{csrf_field()}}--}}
                    {{--</form>--}}
                {{--</div>--}}
                {{--<div class="featured-photo-thumb text-center {{!$hasFeaturedPhoto ? 'choose-featured-photo' : ''}}" data-target="#plan-dropzone-{{$plan->id}}">--}}
                    {{--@if(!$hasFeaturedPhoto)--}}
                        {{--<span class="fa fa-photo fa-2x" style="margin-top: 40%"></span>--}}
                    {{--@endif--}}
                {{--</div>--}}
                {{--<div class="text-center">--}}
                    {{--<a href="{{getImage($plan->featured_photo_path)}}" class="text-danger" data-target="#delete-featured-photo-form-{{$plan->id}}" onclick="triggerTargetSubmit(event, this)">remove</a>--}}
                    {{--<form method="POST" action="/plan/featuredPhoto/{{$plan->id}}" id="delete-featured-photo-form-{{$plan->id}}">--}}
                        {{--{{form_method_field("DELETE")}}--}}
                        {{--{{csrf_field()}}--}}
                    {{--</form>--}}
                {{--</div>--}}
                {{--<hr>--}}
                {{--FEATURED PHOTO END--}}
                {{--<div class="col-md-6 offset-md-3">--}}
                        {{--<div class="text-center">Gallery photos<br>--}}

                            {{--@if(count($plan->photos) < 4)--}}
                                {{--<button class="btn-sm theme-background text-center">--}}
                                    {{--<a class="text-default" data-target="#gallery-dropzone-{{$plan->id}}" onclick="triggerTargetClick(event, this)">{{sprintf('choose up to %s more',4 - count($plan->photos))}}</a>--}}
                                {{--</button>--}}
                                {{--<form class="gallery-photos-form hide" id="gallery-dropzone-{{$plan->id}}" method="POST" action="/plan/galleryPhoto/{{$plan->id}}">--}}
                                    {{--{{csrf_field()}}--}}
                                {{--</form>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                {{--</div>--}}
                {{--<div class="row p-4">--}}
                    {{--@for($i = 0; $i < $maxGalleryCount; $i++)--}}
                        {{--@php--}}
                            {{--$hasGalleryPhoto = isset($plan->photos[$i]);--}}
                            {{--$path    = $hasGalleryPhoto ? $plan->photos[$i]->path : '';--}}
                            {{--$photoId = $hasGalleryPhoto ? $plan->photos[$i]->id : 0;--}}
                        {{--@endphp--}}

                        {{--<div class="col-md-3">--}}

                            {{--<div class="gallery-photo p-3">--}}
                                    {{--<span class="fa fa-photo"></span>--}}
                            {{--</div>--}}
                            {{--<div class="text-center">--}}
                                {{--<a href="{{getImage($path)}}" class="delete-gallery-photo text-danger" data-target="#delete-gallery-photo-form-{{$photoId}}" onclick="triggerTargetSubmit(event, this)"><span class="fa fa-close"></span> </a>--}}
                                {{--<form class="hide" method="POST" action="/plan/galleryPhoto/{{$photoId}}" id="delete-gallery-photo-form-{{$photoId}}">--}}
                                    {{--<input name="_method" type="hidden" value="DELETE">--}}
                                    {{--{{csrf_field()}}--}}
                                {{--</form>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--@endfor--}}
                {{--</div>--}}

            {{--</div>--}}
            {{--<input name="_method" type="hidden" value="PUT">--}}

        {{--</div>--}}
        {{--<div class="modal-footer">--}}
            {{--<input type="hidden" name="_method" value="put" />--}}
            {{--<button type="button" class="btn btn-default theme-background hide-sm-modal" data-dismiss="modal">Done</button>--}}
        {{--</div>--}}
    {{--</div>--}}

{{--</div>--}}

<div id="plan-gallery-{{$plan->id}}" class="sm-modal autoscroll" role="dialog" style="display: block">

    <div class="row" id="create-service-step1">
        <div class="col-md-8 offset-md-2">

            <div id="" class=" card" role="dialog">
                <!-- Modal content-->
                <div class="modal-content col-md-10 offset-md-1">
                    <div class="modal-header">
                        <h4 class="modal-title">Step 1: Add Photos</h4>
                    </div>
                    <div class="modal-body">
                        <div class="plan-preview-photo">
                            <div class="text-center">Featured photo<br><p class="text-danger">*required*</p></div>
                            {{--FEATURED PHOTO START--}}
                            <div class="text-center p-3"  style="border-radius: .3em;">
                                <span class="fa fa-photo fa-2x placeholder" id="trigger-add-featured-photo" style=""></span>
                                <img src="" id="featured-photo-temp" class="featured-photo-temp">
                                <br>
                                <span class="fa fa-close remove text-danger" data-target="#featured-photo-temp" onclick="clearImage(this)" style="display: none"></span>
                            </div>

                            <hr>
                            {{--FEATURED PHOTO END--}}
                            <div class="col-md-8 offset-md-2 p-0">
                                <p class="text-center">Gallery photos<br>up to 4</p>
                                <div class="row">
                                    @for($i=0; $i < 4; $i++)
                                        <div class="col-3 text-center empty ">
                                            <span class="fa fa-2x fa-photo placeholder trigger-add-gallery-photos" style=""></span>
                                            <img src="" id="gallery-photo-temp-{{$i}}">
                                            <br>
                                            <span class="fa fa-close remove text-danger" data-target="#gallery-photo-temp-{{$i}}" onclick="clearImage(this)" style="display: none"></span>
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
        <input type="file" name="featured_photo" id="featured-photo" onchange="readFeaturedImg(this)" style="display: none">
        {{csrf_field()}}
    </form>

    <form class="gallery-photos-form hide" id="gallery-dropzone-{{$plan->id}}" method="POST" action="/plan/galleryPhoto/{{$plan->id}}">
        <input type="file" name="gallery_photos[]" id="gallery-photos" onchange="readImages(this)" style="display: none" multiple>
        {{csrf_field()}}
    </form>

</div>

@section('footer')
    <script src="{{ baseUrlConcat('/js/create-service.js') }}"></script>
@endsection