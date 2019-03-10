<?php

namespace App\Http\Controllers;

use App\Business;
use App\Notification;
use App\Plan;
use App\Subscription;
use App\SubscriptionService;
use App\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Billable;
use Stripe\Customer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SubscriptionController extends Controller
{
    use Billable;

    /**
     * The following are potential statuses for subscriptions
     * trialing
     * active
     * past_due
     * canceled
     * unpaid
     */

    public function showSubscriptionForm($planId)
    {
        /** @var Builder $allBusinessPlans */
        $interval = null;
        $price = null;
        if(strpos($planId, "year") > -1) {
            $planId = str_replace('_year','',$planId);
            $interval = "year";
        } elseif(strpos($planId, "month") > -1) {
            $planId = str_replace('_month','',$planId);
            $interval = "month";
        } else {
            throw new NotFoundHttpException("This page does not exist");
        }
        $planToSubscribe = DB::table('plans')->where('stripe_plan_id',$planId)->first();
        $publicStripeKey = getPublicStripeKey();
        if($interval == "year")
            $price = $planToSubscribe->year_price;
        else {
            $price = $planToSubscribe->month_price;
        }

        $data = [
          'publicStripeKey' => $publicStripeKey,
          'planToSubscribe' => $planToSubscribe,
          'interval'        => $interval,
          'price'           => $price
        ];

        return view('subscription.subscriptionForm')->with('data',$data);
    }


    public function createSubscription(Request $request, $portal = null)
    {
        /** @var User $user */
        if(Auth::id() <= 0 || Auth::id() != $request->user_id) {
            throw new AuthorizationException("You are not authorized to make this request");
        }

        setStripeApiKey("secret");
        $stripeToken    = $request->stripeToken;
        $planName       = $request->stripe_plan_name;
        $planIdentifier = $request->stripe_plan_id;
        $otruvezPlanId       = $request->plan_id;
        $businessId     = $request->business_id;
        $isAppPlan      = $request->is_app_plan;
        $price          = $request->price;
        $interval       = $request->o_interval;
        $user           = Auth::user();
        // First create the subscription in stripe
        $newStripeSubscription = $user->newSubscription($planName,$planIdentifier)->create($stripeToken);
        // if the above fails, we need to cancel the subscription
//        $subscription   = \Stripe\Subscription::retrieve($subscriptionId);
//        $subscription->cancel();

        $newStripeSubscription->last_usage_date     = currentMonthAndYear(); // restart this process
        $newStripeSubscription->business_id         = $businessId;
        $newStripeSubscription->price               = $price;
        $newStripeSubscription->o_interval         = $interval;
        $newStripeSubscription->plan_id             = $otruvezPlanId;
        $newStripeSubscription->save();

        if($isAppPlan) {
            (new UserController())->activateBusinessAccount($user->id, $planIdentifier,$newStripeSubscription->id);
            return redirect('/business');
        }
        $business = Business::find($businessId);
        (new Notification())->sendSubscribedUserNotification($user,$business, $newStripeSubscription);

        if($request->has('apiKey') && validatePortalParams($businessId, $otruvezPlanId, $request->get('apiKey')) != null) {
            return redirect()->to("/account/mysubscriptions/$businessId");
        }

        return redirect('/subscription/subscribed')
            ->with('interval', $interval)
            ->with('price', $price)
            ->with('planName', $planName);
    }


    public function updateSubscription(Request $request)
    {
        // NOTE: to pause an account, just set it to a "free" account.
        // This needs to be created in Stripe

        /** @var User $user */
        $user                 = Auth::user();
        $subscriptionId       = $user->subscription_id;
        $newPlan              = $request->plan_id;
        setStripeApiKey("secret");
        $subscription   = \Stripe\Subscription::retrieve($subscriptionId);
        $itemID         = $subscription->items->data[0]->id;

        \Stripe\Subscription::update($subscriptionId, array(
            "items" => array(
                array(
                    "id" => $itemID,
                    "plan" => $newPlan,
                ),
            ),
        ));
    }


    public function cancelSubscription(Request $request, $subscriptionId)
    {
        setStripeApiKey("secret");
        $localSubscription  = Subscription::find($subscriptionId);
        $user               = User::find($localSubscription->user_id);
        $business           = Business::find($localSubscription->business_id);
        $refundMsg          = '';
        try {
            /** @var User $user */

            if($user->id == Auth::id() || $business->user_id == Auth::id()) {
                // if condition passes, we know that the user either owns or created the subscription and we're ok to go on
                if($business->user_id == Auth::id()) {
                    $refund = Subscription::getRefundStatusAndAmount($localSubscription);
                    if($refund['amount']) {
                        $refundMsg = sprintf("A refund of %s was issued to the customer", $refund['amount']);
                        $data['subject']    = "Refund Notice";
                        $data['body']       = sprintf("You will be issued a refund of %s in 2 to 3 business days. If there is an issue, please contact Otruvez Support at otruvez@gmail.com", $refund['amount']);
                        (new Notification())->sendMessageToCustomersNotification($business, $localSubscription, $data);
                    }
                }
            } else {
                abort(403,"You are not authorized to make this request homie.");
            }
            Notification::where('subscription_id',$subscriptionId)->delete();

            $stripeSubscription = \Stripe\Subscription::retrieve($localSubscription->stripe_id);
        } catch (Exception $e) {
            logException($e);
            return redirect()->back()->with("errorMessage", "There was a problem canceling your subscription. please try again or contact customer service {$localSubscription->stripe_id} {$subscriptionId} {$e->getMessage()}");
        }

        (new Notification())->sendUnsubscribedUserNotification($user,$business, $localSubscription);
        $localSubscription->delete();
        try {
            $stripeSubscription->cancel(); // need a catch here
        } catch (Exception $e) {
            logException($e);
//            return redirect()->back()->with('warningMessage',"There was a problem deleting the service because it may no longer exist. please contact support with the following id if problems persist: {$localSubscription->stripe_id}");
        }



        $isBusinessAccount = isset($request->is_business_account) ? $request->is_business_account : "0";

        if($isBusinessAccount) {
            $user->business_account      = "0";
            $user->business_account_plan = null;
            $user->subscription_id       = null;
            $user->save();
        }

        return redirect()->back()->with("successMessage", "Subscription cancelled Successfully. $refundMsg");

    }


    public function getSubscriptionStatus()
    {
        /** @var User $user */
        setStripeApiKey("secret");
        $user           = Auth::user();
        $subscriptionId = $user->subscription_id;
        $subscription   = \Stripe\Subscription::retrieve($subscriptionId);
        return $subscription->status;
    }

    public function checkIn(Request $request, $planId, $subscriptionId)
    {
        /** @var Subscription $subscription */
        $userId       = Auth::id();
        $plan         = Plan::find($planId);
        $subscription = Subscription::find($subscriptionId);
        $planInterval = $plan->limit_interval;

        if(!$planInterval) {
            return 'This service does not require checkin';
        }


        if (isUsageLimitExceeded($subscription, $plan))
        {
            return 'You have exceeded your usage limit for this period';

        }

        $checkinCode = rand(10000,99999);
        $subscription->checkin_code = $checkinCode;
        $subscription->last_usage_date = currentMonthAndYear();
        $subscription->is_checking_in = "1";
        $subscription->save();

        return $checkinCode;
    }

    public function getCheckIns()
    {
        $checkIns = DB::table('subscriptions')
                        ->where('business_owner_id',Auth::id())
                        ->where('is_checking_in',1)
                        ->get();
        return $checkIns;
    }

    public function confirmCheckin(Request $request, $subscriptionId)
    {
        /** @var Subscription $subscription */
        $subscription = Subscription::find($subscriptionId);

//
        if($subscription && $subscription->checkin_code == $request->checkin_code && $subscription->is_checking_in == 1) {
            $subscription->uses = $subscription->uses + 1;
            $subscription->is_checking_in = 0;
            $subscription->save();

            return 1;
        } else {
            return 0;
        }
    }

    public function resetCheckIn(Request $request)
    {
        /** @var Subscription $subscription */
        $subscription = DB::table('subscriptions')->where('id',$request->id)->get();
        $subscription->is_checking_in = "0";
        $subscription->save();
    }

    public function subscribed()
    {
        return view('subscription.subscribed');
    }

}

