<?php

namespace App\Http\Controllers;

use App\Business;
use App\Plan;
use App\Subscription;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;

class ApiBusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getStatus(Request $request)
    {
//        $this->validate($request->(),[
//            'entity_id'         => 'required',
//            'api_key'           => 'required',
//            'service_id'        => 'required',
//            'customer_email'    => 'required|email',
//        ]);

        $business = (new Business())->find($request->json('entity_id'));

        if(!$business) {
            return Response::create([
                'message' => 'Entity not found'
            ], 404);
        }

        if($business->api_key != $request->json('api_key')) {
            return Response::create([
                'message' => "You're not authorized to make this request"
            ], 403);
        }

        $plan = (new Plan())->find($request->json('service_id'));

        if(!$plan || $plan->business->id != $request->json('entity_id')) {
            return Response::create([
                'message' => "You're not authorized to make this request"
            ], 403);
        }

        $user = (new User())->where('email', $request->json('customer_email'))->first();

        if(!$user || $user->activated == "0") {
            return Response::create([
                'message' => 'Customer not found or inactive'
            ], 404);
        }

        $subscription = (new Subscription())->where('user_id', $user->id)->where('plan_id', $plan->id)->first();

        if(!$subscription) {
            return Response::create([
                'message' => 'No subscription exists for user'
            ], 404);
        }

        return Response::create([
            'account_status' => $user->has_valid_payment_method ? 'paid' : 'unpaid',
            'customer_email' => $user->email,
            'service_name'   => $plan->stripe_plan_name,
            'message' => 'Successful request'
        ], 200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelSubscription($id)
    {
        //
    }
}
