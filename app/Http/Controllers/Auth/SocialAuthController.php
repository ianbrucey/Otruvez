<?php

namespace App\Http\Controllers\Auth;

use App\Email;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;

class SocialAuthController extends Controller
{
    protected $redirectTo = '/account';
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function redirectToProviderPortal($provider, $businessId ,$stripeId ,$apiKey)
    {
        session([
            'businessId' => $businessId,
            'stripeId'   => $stripeId,
            'apiKey'     => $apiKey
        ]);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @param $provider
     * @return Response
     */
    public function handleProviderCallback($provider)
    {

        if(session('apiKey')) {
            $portalExtension = sprintf('/%s/%s/%s', session('businessId'), session('stripeId'), session('apiKey'));
            $this->redirectTo = sprintf('/portal/viewService%s',$portalExtension);
        }

        $user = Socialite::driver($provider)->user();

        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        // REDIRECT IS SET BASED ON SITUATION
        return redirect($this->redirectTo);
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }
        $nameArray = explode(" ", $user->name);
        $data = [];
        $data['first']      = $nameArray[0];
        $data['last']       = $nameArray[1];
        $data['email']      = $user->email;
        $data['provider']   = $provider;
        $data['provider_id'] = $user->id;

        return $this->create($data);
    }

    protected function create(array $data)
    {

        $stripeSecretKey = config('services.stripe.secret');

        Stripe::setApiKey($stripeSecretKey);

        // Create the Stripe Customer
        $stripeCustomer = \Stripe\Customer::create([
            'email' => $data['email'],
            'description' => sprintf("account for %s %s | %s",$data['first'],$data['last'],$data['email']),
        ]);

        // if we have a provider, we don't need to verify the user as a bot or not

        $activationToken = generateValidationToken();

        $user = (new User())->create([
            'first' => $data['first'],
            'last' => $data['last'],
            'email' => $data['email'],
            'stripe_id' => $stripeCustomer->id,
            'activated' => issetAndTrue($data['provider']) ? "1" : "0",
            'activation_token' => $activationToken,
            'provider' => issetAndTrue($data['provider']),
            'provider_id' => issetAndTrue($data['provider_id'])
        ]);

        if($user->activated != 1) {
            Email::sendConfirmAccountEmail($user, $activationToken);
        }

        return $user;
    }
}
