@extends('layouts.app')
@section('body')
    @include('partials.business-back')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h3 class="text-center">Payout Options</h3>
            <p class="text-center">
                When your payment is ready, we will need somewhere to send your check.
                By default we use your business address. If you would like your check sent else where,
                please enter your address below, along with any suite, building or room numbers.
            </p>

            <h6 class="text-center">Current payout address: <span class="theme-color">{{ $payoutAddress ? $payoutAddress : 'No address selected' }}</span></h6>

            <form action="/business/updatePayout" method="post" class="text-center">
                {{csrf_field()}}
                {{form_method_field('PUT')}}
                <label>Address</label>
                <input class="form-control" name="payout_address" placeholder="Enter address" required>
                <button class="btn theme-background text-white mt-4" type="submit">Submit</button>
            </form>

        </div>
    </div>

@endsection