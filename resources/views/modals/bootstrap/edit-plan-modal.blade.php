<div id="plan-edit-{{$plan->id}}" class="sm-modal autoscroll">
    <form method="POST" action="/plan/update/{{$plan->id}}">
        {{csrf_field()}}
        <!-- Modal content-->
        <div class="modal-content col-md-8 offset-md-2">
            <div class="modal-header">

                <h4 class="modal-title">Edit Plan Details</h4>
                <button type="button" class="hide-sm-modal float-right" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="">
                    <label>Service Name: </label>
                </div>
                <div class="">
                    <input type="text" name="stripe_plan_name" class="form-control" value="{{$plan->stripe_plan_name}}">
                </div>

                <div class="">
                    <label>Service Description:</label>
                </div>
                <div class="">
                    <textarea name="description" class="form-control">{{$plan->description}}</textarea>
                </div>
                <input name="_method" type="hidden" value="PUT">

            </div>
            <div class="modal-footer">
                <input type="hidden" name="_method" value="put" />
                <button type="submit" class="btn theme-background float-left">Save Changes</button>
                <button type="button" class="btn btn-default hide-sm-modal" data-dismiss="modal">Cancel</button>
            </div>
        </div>

    </form>
</div>