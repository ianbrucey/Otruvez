<div id="plan-gallery-{{$plan->id}}" class="sm-modal autoscroll" role="dialog">
    <!-- Modal content-->
    <div class="modal-content col-md-8 offset-md-2">
        <div class="modal-header">
            <button type="button" class="hide-sm-modal float-left" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Photos</h4>
        </div>
        <div class="modal-body">
            <div class="plan-preview-photo">
                <div class="text-center">Featured photo</div>
                {{--FEATURED PHOTO START--}}
                <? $hasFeaturedPhoto = !empty($plan->featured_photo_path); ?>
                <p class="text-center">
                    <a class="text-primary" data-target="#plan-dropzone-{{$plan->id}}" onclick="triggerTargetClick(event, this)">update</a>
                    <form method="POST" action="/plan/featuredPhoto/{{$plan->id}}" class="dropzone text-center hide" id="plan-dropzone-{{$plan->id}}" >
                        <span data-dz-message class="fa fa-photo fa-1x dz-message"><br>Add a featured image</span>
                        {{csrf_field()}}
                    </form>
                </p>
                <div style="background: url({{$hasFeaturedPhoto ? getImage($plan->featured_photo_path) : ''}}) center"  class="featured-photo-thumb text-center {{!$hasFeaturedPhoto ? 'choose-featured-photo' : ''}}" data-target="#plan-dropzone-{{$plan->id}}" href="{{$hasFeaturedPhoto ? getImage($plan->featured_photo_path) : ''}}" {{$hasFeaturedPhoto ? 'data-lity' : ''}}>
                    @if(!$hasFeaturedPhoto)
                        <span class="fa fa-photo fa-2x" style="margin-top: 40%"></span>
                    @endif
                </div>
                <p class="text-center">
                    <a href="{{getImage($plan->featured_photo_path)}}" class="text-danger" data-target="#delete-featured-photo-form-{{$plan->id}}" onclick="triggerTargetSubmit(event, this)">remove</a>
                    <form method="POST" action="/plan/featuredPhoto/{{$plan->id}}" id="delete-featured-photo-form-{{$plan->id}}">
                        {{form_method_field("DELETE")}}
                        {{csrf_field()}}
                    </form>
                </p>
                <hr>
                {{--FEATURED PHOTO END--}}
                <div class="col-md-6 offset-md-3">
                        <div class="text-center">Gallery photos<br>

                            @if(count($plan->photos) < 4)
                                <button class="btn theme-background text-center">
                                    <a class="text-default" data-target="#gallery-dropzone-{{$plan->id}}" onclick="triggerTargetClick(event, this)">{{sprintf('choose up to %s more',4 - count($plan->photos))}}</a>
                                </button>
                                <form class="dropzone hide" id="gallery-dropzone-{{$plan->id}}" method="POST" action="/plan/galleryPhoto/{{$plan->id}}">
                                    {{csrf_field()}}
                                </form>
                            @endif
                        </div>
                </div>
                <div class="row p-4">
                    @for($i = 0; $i < $maxGalleryCount; $i++)
                        @php
                            $hasGalleryPhoto = isset($plan->photos[$i]);
                            $path    = $hasGalleryPhoto ? $plan->photos[$i]->path : '';
                            $photoId = $hasGalleryPhoto ? $plan->photos[$i]->id : 0;
                        @endphp

                        <div class="col-md-3" style="padding-top: 20px;">

                            <div style="background: url({{ $hasGalleryPhoto ? getImage( $path) : ''}})" class="gallery-photo" href="{{ $hasGalleryPhoto ? getImage($path) : ''}}" {{$hasGalleryPhoto ? 'data-lity' : ''}}>
                                @if(!$hasGalleryPhoto)
                                    <span class="fa fa-photo fa-2x" style="margin-top: 30%; margin-left: 28%"></span>
                                @endif
                            </div>
                            @if($hasGalleryPhoto)
                                <div class="text-center">
                                    <a href="{{getImage($path)}}" class="delete-gallery-photo text-danger" data-target="#delete-gallery-photo-form-{{$photoId}}" onclick="triggerTargetSubmit(event, this)"><span class="fa fa-close"></span> </a>
                                <form class="hide" method="POST" action="/plan/galleryPhoto/{{$photoId}}" id="delete-gallery-photo-form-{{$photoId}}">
                                    <input name="_method" type="hidden" value="DELETE">
                                    {{csrf_field()}}
                                </form>
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>

            </div>
            <input name="_method" type="hidden" value="PUT">

        </div>
        <div class="modal-footer">
            <input type="hidden" name="_method" value="put" />
            <button type="button" class="btn btn-default theme-background hide-sm-modal" data-dismiss="modal">Done</button>
        </div>
    </div>

</div>