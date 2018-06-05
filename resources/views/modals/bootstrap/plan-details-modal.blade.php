<div id="plan-details-{{$plan->id}}" class="sm-modal autoscroll" role="dialog">
    <!-- Modal content-->
        <div class="modal-content col-md-8 offset-md-2">
            <div class="modal-header">
                <h4 class="modal-title">Plan Details</h4>
                <button type="button" class="hide-sm-modal float-right" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="">
                    <h3>{{$plan->stripe_plan_name}}</h3>
                    <hr>
                </div>
                {{--<div class="plan-preview-photo">--}}
                    {{--<h4 class="text-center">Featured photo</h4>--}}
                    {{--@if($plan->featured_photo)--}}
                        {{--<span class="fa fa-user fa-1x"></span>--}}
                    {{--@else--}}
                        {{--<div class="text-center" style="margin: 5px; padding: 5px; border: 1px solid cornflowerblue; border-radius: 5px">--}}
                            {{--<p class="text-muted">No featured image.</p>--}}
                            {{--<button class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#plan-gallery-{{$plan->id}}">--}}
                                {{--<span class="fa fa-photo"> click to add</span>--}}
                            {{--</button>--}}
                        {{--</div>--}}
                    {{--@endif--}}
                    {{--<h4 class="text-center">Photo gallery</h4>--}}
                        {{--<div class="text-center" style="margin: 5px; padding: 5px; border: 1px solid cornflowerblue; border-radius: 5px">--}}
                            {{--<p class="text-muted">No gallery photos</p>--}}
                            {{--<button class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#plan-gallery-{{$plan->id}}">--}}
                                {{--<span class="fa fa-photo"> click to add</span>--}}
                            {{--</button>--}}
                        {{--</div>--}}
                {{--</div>--}}
                <div class="">
                    <label>{{getUseLimitString($plan)}} </label>
                </div>


                <div class="">
                    <label>Service Description:</label>
                </div>
                <div class="">
                    <p>{{$plan->description}}</p>
                    <hr>
                </div>
                <div class="">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <h3>Price</h3>
                            </tr>
                        </thead>
                        <thead>
                        <tr>
                            <th>Monthly</th>
                            <th>Annual</th>
                        </tr>
                        </thead>
                        <tbody>
                            <td>{{formatPrice($plan->month_price)}}</td>
                            <td>{{formatPrice($plan->year_price)}}</td>
                        </tbody>
                    </table>
                </div>
                <input name="_method" type="hidden" value="PUT">

            </div>
            <div class="modal-footer">
                <input type="hidden" name="_method" value="put" />
                <button type="button" class="btn btn-default hide-sm-modal theme-background" data-dismiss="modal">Done</button>
            </div>
        </div>

</div>