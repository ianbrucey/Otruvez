<div class="row" id="create-service-step1">
    <div class="col-md-8 offset-md-2">

        <div class="text-center m-4"> <button class="btn btn-sm theme-background create-service-next-step" data-hide="#create-service-step1" data-show="#create-service-step2" disabled>Next Step</button></div>

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
