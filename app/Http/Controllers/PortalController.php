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
    private   $params;
    protected $portalRouteExtension = '';
    protected $loginRoute;
    protected $registerRoute;
    protected $viewServiceRoute;
    protected $confirmAccountRoute;

    public function __construct(Request $request)
    {
        $this->middleware('portal-guest', ['except' => [
            'showLogin',
            'showRegister'
        ]]);

        $this->params = [
            'businessId'    => $request->route('businessId'),
            'stripeId'      => $request->route('stripeId'),
            'apiKey'        => $request->route('apiKey'),
            'customerEmail' => $request->route('customerEmail')

        ];

        $this->portalRouteExtension = sprintf("/%s/%s/%s",$this->params['businessId'],$this->params['stripeId'],$this->params['apiKey']);
        $this->loginRoute = sprintf("/portal/login%s",$this->portalRouteExtension);
        $this->registerRoute = sprintf("/portal/register%s",$this->portalRouteExtension);
        $this->viewServiceRoute = sprintf("/portal/viewService%s",$this->portalRouteExtension);
        $this->confirmAccountRoute = sprintf("/portal/confirmAccount%s",$this->portalRouteExtension);

    }

    public function showLogin($businessId ,$stripeId ,$apiKey, $customerEmail = null) {

        if(Auth::check()) {
            return redirect($this->viewServiceRoute);
        }

        if(validatePortalParams($businessId ,$stripeId ,$apiKey)) {
            return view('portal/auth/portal-login')->with($this->params)->with([
                'registerRoute'         => $this->registerRoute,
                'portalRouteExtension'  => $this->portalRouteExtension
            ]);
        } else {
            return abort(404);
        }


    }

    public function showRegister($businessId ,$stripeId ,$apiKey) {
        if(Auth::check()) {
            return redirect($this->viewServiceRoute);
        }

        if(validatePortalParams($businessId ,$stripeId ,$apiKey)) {
            return view('portal/auth/portal-register')->with($this->params)->with([
                'loginRoute'            => $this->loginRoute,
                'portalRouteExtension'  => $this->portalRouteExtension
            ]);
        } else {
            return abort(404);
        }

    }

    public function showService($businessId ,$stripeId ,$apiKey) {
        if(!Auth::check()) {
            $this->loginRoute = sprintf('/portal/login/%s/%s/%s',$businessId ,$stripeId ,$apiKey);
            return redirect($this->loginRoute);
        }
        
        $user = Auth::user();

        if($user->activated != "1") {
            return redirect($this->confirmAccountRoute);
        }

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
            return abort(404);
        }
    }

    public function showConfirmAccount() {
        if(!Auth::check()) {
            return redirect($this->loginRoute);
        }

        if(Auth::user()->activated == "1") {
            return redirect($this->viewServiceRoute);
        }

        return view('confirm-account');
    }


}
