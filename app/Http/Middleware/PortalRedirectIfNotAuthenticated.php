<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PortalRedirectIfNotAuthenticated
{
    protected $portalRouteExtension;
    protected $loginRoute;
    protected $registerRoute;
    protected $viewServiceRoute;
    protected $confirmAccountRoute;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $businessId = $request->route('businessId');
        $stripeId   = $request->route('stripeId');
        $apiKey     = $request->route('apiKey');
        $this->portalRouteExtension = sprintf("/%s/%s/%s",$businessId,$stripeId,$apiKey);
        $this->loginRoute = sprintf("/portal/login%s",$this->portalRouteExtension);
        if (!Auth::check()) {
            return redirect($this->loginRoute);
        }

        return $next($request);
    }
}
