<h4>Heads up! A subscription you own will be cancelled because we have removed it from our services:</h4>
<hr>
<ul style="width: 100%">
    <li>Subscription name: <h3>{{$serviceName}}</h3></li>

    <li>Confirmation ID: <p style="color: black">{{$confirmationId}}</p></li>

    <li>Refund Status: {{$refundStatus['refund'] ? sprintf("a refund of %s will be issued back to you", $refundStatus['amount']) : 'No refund is due'}}</li>

</ul>

<p>We apologize for any inconvenience this may have caused</p>
<hr>
<div class="row text-center">
    @if($logoPath)
        <div class="col-12">
            <h3 class="text-center m-3">{{$companyName}} thanks you</h3>
        </div>
        <div style="margin: auto; margin-bottom: 5px; width: 70px; height: 70px; background: url({{ getImage($logoPath) }}) no-repeat; background-size: contain"> </div>
    @endif
</div>