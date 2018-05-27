<div id="subscription-details-{{$plan->id}}" class="sm-modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div id="" class="row" role="dialog">
        <div class="col-md-6 offset-md-3">

            <!-- Modal content-->
            <div class="theme-background" style="border-radius: 5%">
                <div class="card-header text-white-children">
                    <h4>Subscription Details for: <br></h4>
                    <h5>{{ucfirst(removeLastWord($plan->stripe_plan_name))}}</h5>
                </div>
                <form method="post" action="/business/create" class="form-group-md">
                    <div class="card-body text-white-children">
                        <h4><u>Price</u></h4>
                        @if($plan->month_price)
                            <h3>Monthly: {{formatPrice($plan->month_price)}}</h3>
                        @endif
                        @if($plan->year_price)
                            <h3>Yearly: {{formatPrice($plan->year_price)}}</h3>
                        @endif
                        <hr>
                        <h4><u>Description</u></h4>
                        <h5>{{$plan->description}}</h5>
                        <hr>
                        <h4><u>Usage Limit</u></h4>
                        <h3 class="card-text">{{getUseLimitString($plan)}}</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-white pull-left hide-sm-modal" data-dismiss="modal">Done</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>