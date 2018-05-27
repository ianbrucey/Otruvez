<div id="rate-{{$plan->id}}" class="sm-modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close hide-sm-modal" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="card-body text-center">
                <h2 class="text-center">Do you like this service? please rate it</h2>

                    <h1 style="display: inline" class="mr-4"><span class="fa fa-frown-o"></span></h1>
                    @for($i = 1; $i <=5; $i++)

                        <h3 style="display: inline" class="rate-star"><span class="fa fa-star-o fa-lg star-icons" data-rate="{{$i}}"></span></h3>

                    @endfor
                    <h1 style="display: inline" class="ml-4"><span class="fa fa-smile-o"></span></h1>

                <form method="post" action="/rating/rateService/{{$plan->id}}" class="form-group">
                    <input type="hidden" name="rate_number" id="rate-number">
                    <br>
                    <button type="submit" class="btn btn-success">Rate this product</button>
                    {{csrf_field()}}
                </form>


            </div>
        </div>
    </div>
</div>

<script>
    $('.rate-star').on('click', function() {
        var span = $(this).children();
        $('.star-icons').addClass('fa-star-o');
        var rateNumber = $(this).children().attr('data-rate');
        span.removeClass('fa-star-o').addClass('fa-star');
        $('#rate-number').val(rateNumber);


    });
</script>