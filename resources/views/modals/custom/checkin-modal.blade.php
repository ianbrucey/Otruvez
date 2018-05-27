<div id="checkin-{{$subscription->id}}" class="sm-modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-content col-md-6 offset-md-3 theme-background">
        <div class="modal-header">
            <button type="button" class="close btn bg-white hide-sm-modal mt-2 mb-2" data-dismiss="modal">Done</button>
        </div>
        <div class="modal-body text-white-children">
            <h1 class="text-center">Check-in</h1>
            <div id="checkin-response-container">
                <h3 class="text-center">Give this 5 digit code to the service worker to use their service.</h3>
                <hr>
                <h1 class="text-center checkin-code bg-white theme-color">{{getLoadingAnimation()}}</h1>
            </div>
        </div>
    </div>
</div>