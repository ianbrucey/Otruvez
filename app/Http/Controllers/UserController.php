<?php

namespace App\Http\Controllers;

use App\Email;
use App\Notification;
use App\S3FolderTypes;
use App\Subscription;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Mockery\CountValidator\Exception;
use Mockery\Matcher\Not;
use App\PhotoClient\AWSPhoto;

class UserController extends Controller
{

    public function __construct()
    {
        $this->s3 = new AWSPhoto();
    }

    private $s3;

    public function activateBusinessAccount($id, $accountPlan, $subscriptionId)
    {
        $user = Auth::user();
        if ($user)
        {
            $user->business_account = "1";
            $user->business_account_plan = $accountPlan;
            $user->subscription_id = $subscriptionId;
            $user->save();
            return $user;
        }

        return 'success';

    }

    public function activateUserAccount(Request $request)
    {
        $this->validate($request,[
           'email' => 'required|email',
           'token' => 'required|'.ALPHANUMERIC_DASH_SPACE_DOT_REGEX
        ]);

        $user = (new User())->where('email', $request->query('email'))->first();

        if($user && $user->activated == 0 && $user->activation_token == $request->query('token')) {
            $user->activated = "1";
            $user->save();
            $notification = new Notification();
            $notification->sendWelcomeNotification($user);
            return redirect('/login')->with('successMessage', "Your account has been activated! please log in");
        } elseif($user->activated == 1) {
            return redirect('/login')->with('warningMessage', "This link has expired");
        } else {
            return redirect('/login')->with('errorMessage', "Corrupted link");
        }
    }

    public function updateBusinessAccount($id, $accountPlan = null)
    {
        $user = Auth::user();

        $user->business_account = "1";
        $user->business_account_plan = $accountPlan;
        $user->save();
        return $user;

    }


    private function getUserObject()
    {
        return new User;
    }
    public function findUser($id)
    {
        return $this->getUserObject()->find($id);
    }

    public function regenerateValidationToken(User $user)
    {
        $token = generateValidationToken();
        $user->activation_token = $token;
        $user->save();
        Email::sendConfirmAccountEmail($user, $token);
    }

    public function validateToken(Request $request)
    {
        $this->validate($request,[ // may need to test
            'activation_token' => 'required|integer'
        ]);
        $validToken = 0;
        $save = true;
        if($request->has('activation_token')) {
            $user = Auth::user();
            if ($user->lockout != 1) {
                if ($request->get('activation_token') == $user->activation_token) {
                    $user->activated = "1";
                    $user->validation_tries = 0;
                    $user->lockout = 0;
                    $validToken = 1;
                } else {
                    ++$user->validation_tries;
                    if ($user->validation_tries > 3) {
                        $user->lockout = 1;
                        $validToken = 2;
                    } else {
                        $this->regenerateValidationToken($user);
                        $save = false;
                    }
                }

                if($save) {
                    $user->save();
                }

            } else {
                $validToken = 2; // turn these numbers into constants
            }


            return Response::create(['tokenStatus' => $validToken], 201);

        } else {
            // some method to capture IP, location and other info
            return Response::create(['tokenStatus' => -1], 201);;
        }
    }
}
