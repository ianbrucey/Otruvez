<?php

namespace App\Http\Controllers\Auth;

use App\Email;
use App\Mail\ConfirmAccount;
use App\User;
use App\Http\Controllers\Controller;
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
     * @return void
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
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $stripeSecretKey = config('services.stripe.secret');

        Stripe::setApiKey($stripeSecretKey);

        // Create the Stripe Customer
        $stripeCustomer = \Stripe\Customer::create([
            'email' => $data['email'],
            'description' => sprintf("account for %s %s | %s",$data['first'],$data['last'],$data['email']),
        ]);
        $token  = rand(1,100)*rand(1,10) . time() . $data['email'];

        $activationToken = md5($token);
        $updatedAt = date("Y:m:d H:i:s");

        $user = User::create([
            'first' => $data['first'],
            'last' => $data['last'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'stripe_id' => $stripeCustomer->id,
            'activated' => "0",
            'activation_token' => $activationToken
        ]);

        Email::sendConfirmAccountEmail($user, $activationToken);

        return $user;
    }
}
