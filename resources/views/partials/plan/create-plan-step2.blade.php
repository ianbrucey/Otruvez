<div id="create-service-step2" class="row"style="display: none">
    <div class="col-md-8 offset-md-2">
        <div class="text-center m-4"> <button class="btn btn-sm theme-background create-service-previous-step">Back to photos</button></div>

        <!-- Modal content-->
        <div class="card">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Service details</h4>
                </div>
                <form method="post" action="/plan/createPlan" class="form-group-md" id="create-plan-form" enctype="multipart/form-data">
                    <div class="modal-body">
                        <label>Plan Name</label>
                        <input type="text" name="stripe_plan_name" class="form-control" placeholder="Plan Name">
                        <label>
                            <a data-toggle="collapse" data-target="#pricing-info" class="text-danger">*pricing is final. click here for more info*</a>
                        </label><br>
                        <p id="pricing-info" class="collapse">
                            To protect our customers, we do not allow businesses to update the pricing of their services.
                            If you would like to update date your price, we will provide a specific tool for you to notify your
                            customers of the coming update and to then create a new plan to replace the old one.
                        </p>
                        <label>Monthly Price</label>
                        <input type="number" name="month_price" class="form-control" placeholder="Monthly Price">
                        <label>Annual Price</label>
                        <input type="number" name="year_price" class="form-control" placeholder="Annual Price">



                        <hr style="color: {{getThemeColorValue()}} !important;">
                        <h6 class="theme-color">How many times can customers use this service per month or year? <br></h6>
                        <p class="text-muted">*leave blank if no limit is imposed*</p>

                        <div class="row" >

                            <div class="col-1 pt-2">
                                <input type="radio" name="which_usage_interval" class="which_usage_interval" data-input="#use_limit_month" data-input-other="#use_limit_year" data-label="#ulm-label" data-label-other="#uly-label" checked>
                            </div>
                            <div class="col-3">
                                <input type="number" min="0" name="use_limit_month" id="use_limit_month" class="form-control" placeholder="#">
                            </div>
                            <div class="col-8 pt-3">
                                <h4 class="theme-color" id="ulm-label">times a month</h4>
                            </div>

                            <div class="col-5"><hr class="theme-color"></div>
                            <div class="col-2 text-center pr-0 pl-0 pt-1">or</div>
                            <div class="col-5"><hr class="theme-color"></div>

                            <div class="col-1 pt-2">
                                <input type="radio" name="which_usage_interval" class="which_usage_interval" data-input="#use_limit_year" data-input-other="#use_limit_month" data-label="#uly-label" data-label-other="#ulm-label">
                            </div>
                            <div class="col-3">
                                <input type="number" min="0" name="use_limit_year" id="use_limit_year" class="form-control" placeholder="#" disabled="">
                            </div>
                            <div class="col-8 pt-3">
                                <h4 style="color: lightgrey" id="uly-label">times a year</h4>
                            </div>

                        </div>
                        <hr style="color: {{getThemeColorValue()}}">

                        <label>Service Description</label>
                        <textarea name="description" class="form-control" placeholder="Service Description here..."></textarea>
                        <hr>
                        {{csrf_field()}}
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn theme-background">Submit</button>
                    </div>
                    <input type="file" name="featured_photo" id="featured-photo" onchange="readFeaturedImg(this)" style="display: none">
                    <input type="file" name="gallery_photos[]" id="gallery-photos" onchange="readImg(this)" style="display: none" multiple>
                </form>
            </div>
        </div>

    </div>
</div>