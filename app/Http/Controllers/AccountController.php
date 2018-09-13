<?php

namespace App\Http\Controllers;

use App\Business;
use App\Notification;
use App\Subscription;
use App\User;
use Exception;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('account.account-home');
    }

    public function subscriptions(Request $request, $portalBusinessId = null)
    {   $portalBusiness = null;
        if($portalBusinessId) {
            $portalBusiness = Business::find($portalBusinessId);
        }
        $subscriptions  = Subscription::where('user_id', Auth::id())->where('business_id',"!=",0)->get();
        return view('account.subscriptions')
            ->with('subscriptions', $subscriptions)
            ->with('portalBusiness', $portalBusiness)
            ->with('mustUpdatePaymentMethod', !Auth::user()->has_valid_payment_method);
    }
    public function businessNotificationView($businessId){
        $businessEmail = (new Business())->where('id', $businessId)->value('email');
        $notifications = (new Notification())->getNotifications('business', $businessEmail, $businessId);
        return view('business.business-notifications')->with('notifications', $notifications);
    }

    public function accountNotificationView(){
        $notifications = (new Notification())->getNotifications(Auth::id());
        $hasNewNotifications = hasNewNotifications();
        $user = Auth::user();
        $user->notification_count = 0;
        $user->save();
        // maybe also get common
        return view('account.account-notifications')->with('notifications', $notifications)->with('hasNewNotifications', $hasNewNotifications);
    }

    public function contactSupport(Request $request){
        $this->validate($request,[
            'subject'   => 'nullable|'.ALPHANUMERIC_DASH_SPACE_DOT_REGEX,
            'body'      => 'nullable|'.ALPHANUMERIC_DASH_SPACE_DOT_REGEX
        ]);
        $subject = $request->get('subject');
        $body    = $request->get('body');
        $currentTimestamp   = date('Y-m-d G:i:s');
        $currentMonthAndDay = date('m-d');

        $user = Auth::user();

        if(!$user->customer_service_limit) {
            ++$user->customer_service_limit;
            $user->last_customer_service_contact_date = $currentTimestamp;
            $user->save();
        } elseif($user->customer_service_limit == CUSTOMER_SERVICE_CONTACT_LIMIT ) {
            if($currentMonthAndDay == (new \DateTime($user->last_customer_service_contact_date))->format('m-d')) {
                return redirect('/account')->with('infoMessage', "Request limit reached for the day. We will respond to your previous requests within 24-48 hours");
            } else {
                $user->customer_service_limit = 1;
                $user->last_customer_service_contact_date = $currentTimestamp;
                $user->save();
            }

        } else {
            ++$user->customer_service_limit;
            $user->save();
        }

        try {
            (new Notification())->sendSupportNotification($request);
            return redirect('/account')->with('successMessage', "Your message was successfully sent. You will receive a response in 24-48 hours.");
        } catch (Exception $e) {
            // return with old values
            return redirect('/account/support')
                    ->with('errorMessage', "Message was not sent successfully. Please try again: " . $e->getMessage())
                    ->with('subject', $subject)
                    ->with('body', $body);
        }

    }

    public function showDeleteAccountView(){
        // maybe also get common
        return view('account.delete-account');
    }

    public function deleteAccount(Request $request)
    {
        setStripeApiKey('secret');
        $email = $request->get('email');
        $user = Auth::user();
        if($email !=  $user->email) {
            return redirect()->back()->with("errorMessage","Not authorized to make this request");
        }

        if($user->business_id) {
            $businessController = new BusinessController();
            $businessController->deleteBusiness( $request, $user->business_id, true);
        }

        $localSubscriptions = (new Subscription())->where('user_id', Auth::id())->get();
        foreach ($localSubscriptions as $localSubscription) {
            try {
                \Stripe\Subscription::retrieve($localSubscription->stripe_id)->cancel();
            } catch (Exception $e) {
                logger(sprintf('problem with subscription %s - %s',$localSubscription->stripe_id, $e));
            }
            $localSubscription->delete();
        }

        (new User())->find(Auth::id())->delete();
        Auth::logout();

        return redirect('/')->with('successMessage',"Your account was canceled successfully");

    }

    public function showSupportView(){
        // maybe also get common
        return view('account.support');
    }

    public function showUpdatePaymentView(){
        $user = Auth::user();
        return view('account.update-payment')
            ->with('user', Auth::user())
            ->with('hasValidPaymentMethod', $user->has_valid_payment_method);
    }

    public function updatePaymentMethod(Request $request) {

        setStripeApiKey('secret');
        /** @var User $user */
        $user = Auth::user();

        if (isset($_POST['stripeToken'])){
            try {
                $cu = \Stripe\Customer::retrieve($user->stripe_id); // stored in your application
                $cu->source = $_POST['stripeToken']; // obtained with Checkout
                $cu->save();

                $user->has_valid_payment_method = 1;
                $user->card_brand  = $cu->sources->data[0]->brand;
                $user->card_last_four = $cu->sources->data[0]->last4;
                $user->save();


                return redirect()->back()
                    ->with('successMessage', "Your card details have been updated");
            }
            catch(Exception $e) {

                // Use the variable $error to save any errors
                // To be displayed to the customer later in the page
                $body = $e->getJsonBody();
                $err  = $body['error'];
                $error = $err['message'];

                return redirect()->back()
                    ->with('errorMessage', $error);
            }
            // Add additional error handling here as needed
        }
        return redirect()->back()
            ->with('warningMessage', "Invalid form submission");

    }

}
