<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $redirectTo   = '/home';
    protected $hasApiKey      = false;


    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    /**
     * Where to redirect users after login.
     *
     * @var string
     */


    /**
     * Create a new controller instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {

        $this->middleware('guest')->except('logout');

        if($request->has('apiKey')) {
            $businessId = $request->get('businessId');
            $stripeId   = $request->get('stripeId');
            $apiKey     = $request->get('apiKey');
            $this->hasApiKey = true;
            $paramsAreValid = validatePortalParams($businessId,$stripeId,$apiKey);
            if($paramsAreValid) {
                $this->redirectTo = sprintf("/portal/viewService/%s/%s/%s",$businessId,$stripeId,$apiKey);
            }
        }

    }
}
