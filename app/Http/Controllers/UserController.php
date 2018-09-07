<?php

namespace App\Http\Controllers;

use App\Notification;
use App\S3FolderTypes;
use App\Subscription;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user = $this->findUser($id);
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
        $user = $this->findUser($id);
        if ($user)
        {
            $user->business_account = "1";
            $user->business_account_plan = $accountPlan;
            $user->save();
            return $user;
        }

        return 'success';

    }

    public function test(Request $request) {

        $file = $request->file('file');
        $this->s3->store($file, S3FolderTypes::BUSINESS_LOGO);
        return redirect()->back()->with('successMessage',"it worked!");
    }

    public function testView() {
        $this->s3->unlink("business-logo/22baaed9cb5ab368245aadb077a90e6e7553f6ca.jpg");
        return view('test-view');
    }

    private function getUserObject()
    {
        return new User;
    }
    public function findUser($id)
    {
        return $this->getUserObject()->find($id);
    }

    public function validateToken(Request $request)
    {
        $validToken = 0;
        if($request->has('token')) {
            $user = (new User())->find(Auth::id());
            if($request->get('token') == $user->token) {
                $user->activated = "1";
                $user->validation_tries = 0;
                $user->lockout = 0;
                $validToken = 1;
            } else {
                ++$user->validation_tries;
                if($user->validation_tries == 4) {
                    $user->lockout = 1;
                    $validToken = 2;
                }
            }

            $user->save();

            return $validToken;

        } else {
            // some method to capture IP, location and other info
            return -1;
        }
    }
}
