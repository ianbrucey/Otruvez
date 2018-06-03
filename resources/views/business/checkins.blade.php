@extends('layouts.app')
{{--IT SHOULD BE NOTED THAT $checkin is a Subscription object--}}
@section('body')
    @include('partials.business-back')
   <div class="container-fluid">
       <div class="row">
           <div class="col-md-8 offset-md-2">
               <h3 class="text-center"><u>Active Check-ins</u></h3>
               <p style="font-size: 14px">
                    <ol>
                        <li>1) Ask your customer for the 5 digit code they received when they initiated their check-in</li>
                        <li>2) Input that code into the corresponding field</li>
                    </ol>
               </p>
               <hr>
               @forelse($checkins as $checkin)
                   @php
                        $user = $checkin->user;
                        $plan = $checkin->plan();
                   @endphp
                   <div class="card" id="confirm-checkin-card-{{$checkin->id}}">
                       <h5>Subscriber:</h5>
                       <p>{{$user->first}} {{$user->last}} <br>{{$user->email}}</p>
                       <h4>Service: {{removeLastWord($checkin->name)}}</h4>
                       <div class="card-body">
                           <form class="confirm-checkin-form-{{$checkin->id}}">
                               <input class="form-control text-center" placeholder="ENTER CHECK-IN CODE" name="checkin_code">
                           </form>
                       </div>
                       <div class="card-body text-center">
                           <button type="button" class="btn btn-primary show-sm-modal confirm-checkin" data-subscription-id="{{$checkin->id}}" data-modal-target="#confirm-checkin-modal-{{$checkin->id}}">Confirm Check-in</button>
                       </div>
                   </div>
                   <br><br>
                   @include('modals.custom.confirm-checkin-modal')
               @empty
                   <h2 class="text-primary text-center">No active check-ins</h2>
               @endforelse
           </div>
       </div>
   </div>
@endsection

@section('footer')
    <script src="{{baseUrlConcat('/js/ajax/checkin.js')}}"></script>
@endsection
