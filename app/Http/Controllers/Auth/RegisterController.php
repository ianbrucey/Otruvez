<?php

namespace App\Http\Controllers\Auth;

use App\Email;
use App\Mail\ConfirmAccount;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Stripe\Stripe;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    private $secret = "6LdhMW4UAAAAAGFcIO72FqWsyIThtH9MNpc6vCP9";
    private $reCapUrl = "https://www.google.com/recaptcha/api/siteverify";


    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/registered';

    /**
     * Create a new controller instance.
     *
     * @param Request $request
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first' => 'required|string|max:255',
            'last' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'g-recaptcha-response' => 'required|min:10'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @param Request|null $request
     * @return User
     */
    protected function create(array $data, Request $request = null)
    {

        $stripeSecretKey = config('services.stripe.secret');

        Stripe::setApiKey($stripeSecretKey);

        // Create the Stripe Customer
        $stripeCustomer = \Stripe\Customer::create([
            'email' => $data['email'],
            'description' => sprintf("account for %s %s | %s",$data['first'],$data['last'],$data['email']),
        ]);
        /*
         * OLD VALIDATION
            $token  = rand(1,100)*rand(1,10) . time() . $data['email'];
            $activationToken = md5($token);
            $updatedAt = date("Y:m:d H:i:s");
        * OLD VALIDATION
        */

        // new validation 6 random digits
        $recapResponse = $this->postRecaptchaResponse($request);

        $activationToken = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);

        $user = User::create([
            'first' => $data['first'],
            'last' => $data['last'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'stripe_id' => $stripeCustomer->id,
            'activated' => $recapResponse->success == true ? "1" : "0",
            'activation_token' => $activationToken
        ]);

        if($user->activated != 1) {
            Email::sendConfirmAccountEmail($user, $activationToken);
        }

        return $user;
    }

    private function postRecaptchaResponse(Request $request)
    {
        $postQueryString = sprintf("secret=%s&response=%s", $this->secret, $request->get("g-recaptcha-response"));
        $ch = curl_init($this->reCapUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postQueryString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        return \GuzzleHttp\json_decode($response); // returns std class obj
    }
}
