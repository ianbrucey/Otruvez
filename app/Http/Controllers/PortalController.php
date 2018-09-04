<?php

namespace App\Http\Controllers;

use App\Business;
use App\Plan;
use App\Rating;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    private $params;

    public function __construct(Request $request)
    {

        $this->params = [
            'businessId'    => $request->route('businessId'),
            'stripeId'      => $request->route('stripeId'),
            'apiKey'        => $request->route('apiKey'),
            'customerEmail' => $request->route('customerEmail')

        ];

    }

    public function showLogin($businessId ,$stripeId ,$apiKey, $customerEmail = null) {


        if(validatePortalParams($businessId ,$stripeId ,$apiKey)) {
            return view('portal/auth/portal-login')->with($this->params);
        } else {
            return "Not authorized";
        }


    }

    public function showRegister($businessId ,$stripeId ,$apiKey) {


        if(validatePortalParams($businessId ,$stripeId ,$apiKey)) {
            return view('portal/auth/portal-register')->with($this->params);
        } else {
            return "Not authorized";
        }

    }

    public function showService($businessId ,$stripeId ,$apiKey) {


        if($objArray = validatePortalParams($businessId ,$stripeId ,$apiKey)) {
            $plan               = $objArray['plan'];
            $business           = $objArray['business'];
            $hasPhoto           = !empty($business->photo_path);
            $haslogo            = !empty($business->logo_path);
            $alreadySubscribed  = (new \App\Subscription())->where('user_id', Auth::id())->where('plan_id', $stripeId)->exists();
            $owner              = $business->user ? $business->user->id == Auth::id() : false;
            $publicStripeKey    = getPublicStripeKey();
            $rating             = (new Rating())->where('plan_id', $stripeId)->avg('rate_number');
            $reviews            = (new Review())->where('business_id', $business->id)->orderBy('id','desc')->get();
            $hasReview          = (new Review())->where('business_id', $business->id)->where('user_id', Auth::id())->first();
            return view('portal/service/portal-service')
                ->with('hasPhoto',$hasPhoto)
                ->with('haslogo',$haslogo)
                ->with('business',$business)
                ->with('hasReview',$hasReview)
                ->with('reviews',$reviews)
                ->with('rating',$rating)
                ->with('alreadySubscribed',$alreadySubscribed)
                ->with('active','')
                ->with('publicStripeKey',$publicStripeKey)
                ->with('plan',$plan)
                ->with('owner',$owner)
                ->with($this->params);
        } else {
            return "Not authorized";
        }
    }


}
