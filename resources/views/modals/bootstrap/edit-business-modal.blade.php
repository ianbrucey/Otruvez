<div id="business-details-{{$business->id}}" class="sm-modal " style="overflow: scroll">
    <form method="post" action="/business/update/{{$business->id}}" id="edit-business-details-form" class="validate-create-business">
        <input type="text" name="name" class="form-control" value="{{$business->name}}">
        <!-- Modal content-->
        <div class="modal-content col-md-8 offset-md-2">
            <div class="modal-header">
                <h4 class="modal-title">Edit Business Details</h4>
                <button type="button" class="btn-sm theme-background hide-sm-modal" >&times;</button>
            </div>
            <div class="modal-body">
                <h3>{{$business->name}}</h3>
                <div class="edit-label-div">
                    <label>Email: </label>
                </div>
                <div class="edit-input-div">
                    <input type="text" name="email" class="form-control" value="{{$business->email}}">
                </div>

                <div class="edit-label-div">
                    <label>Phone: </label>
                </div>
                <div class="edit-input-div">
                    <input type="tel" name="phone" class="form-control" value="{{$business->phone}}">
                </div>

                <div class="edit-label-div">
                    <label>Address:</label>
                </div>
                <div class="edit-input-div">
                    @include('partials.location.auth-set-address')
                </div>

                <div class="edit-label-div">
                    <label>Business Description:</label>
                </div>
                <div class="edit-input-div">
                    <textarea name="description" class="form-control">{{$business->description}}</textarea>
                </div>

                <hr>
                <div class="">
                    <label data-toggle="collapse" data-target="#redirect-url-info">Redirect Url: <span class="theme-color">What's this?</span></label>
                    <p class="theme-color collapse" id="redirect-url-info" >This field is for online businesses who want to use our portal to sell their subscriptions. After a customer completes the process, they will be redirected to this URL. You can also set this field in the <b>API & Online Business Integration</b> Page for any of the services you offer.</p>
                </div>
                <div class="">
                    <input class="form-control" value="{{$business->redirect_to ?: ''}}" placeholder="www.example.com/thanks" type="text" id="redirect-to-url" name="redirect_to">
                </div>
                <hr>

                <h5><b><a data-toggle="" data-target="hours">Business hours</a></b></h5>
                <div class="business-hours hours" style="display: block">
                    @foreach($days as $day)
                        <div class="edit-label-div">
                            <label>{{ucfirst($day)}}</label>
                        </div>
                        <div class="edit-input-div">
                            <input type="text" name="{{$day}}" class="form-control" value="{{$business->$day}}">
                        </div>

                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="_method" value="put" />
                <button type="submit" class="btn btn-primary pull-left">Save Changes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    {{csrf_field()}}
    </form>
</div>
<script>
    $('.address-container').children("h4, p").addClass("theme-color");
</script>