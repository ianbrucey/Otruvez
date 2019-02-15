<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Stripe\Refund;

class Subscription extends Model
{
    protected $fillable = [
        'name',
        'stripe_id',
        'stripe_plan',
        'plan_id',
        'business_owner_id',
        'status',
        'quantity',
        'is_checking_in',
        'last_usage_date',
        'uses',
        'trial_ends_at',
        'ends_at'
    ];

    public function getCustomer() {
        return $this->belongsTo('App\User');
    }

    public function getSubscriptionService() {
        return $this->belongsTo('App\SubscriptionService');
    }

    public function plan() {
        return Plan::where('id',$this->plan_id)->first();
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public static function getRefundStatusAndAmount($subscription) {

        setStripeApiKey('secret');
        $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_id);
        $plan = (new Plan())->find($subscription->plan_id);
        $planLimit = $subscription->o_interval == 'month' ? $plan->use_limit_month : $plan->use_limit_year;
        $todaysDate = new DateTime();
        $paidDate = new DateTime();
        $paidDate->setTimestamp($stripeSubscription->current_period_start);
        $refundStatus = [
            'refund' => false,
            'amount' => 0
        ];

        // not sure about the paid date logic....
        if($paidDate >= $todaysDate && $subscription->uses != $planLimit && $subscription->uses < $planLimit) { // if user is at usage limit, no refund, else prorate
            $refundStatus['refund'] = true;
            $refundAmount = ($subscription->uses / $planLimit) * $subscription->price;
            $refundStatus['amount'] = formatPrice($subscription->price);

            self::issueRefund($subscription, $refundAmount); // here we will issue the refund
        }

        return $refundStatus;
    }

    public static function issueRefund($subscription, $amount = null) {

        setStripeApiKey('secret');
        $refundArray = [];
        $refundArray['charge'] = $subscription->last_charge_id;
        if($amount) {
            $refundArray['amount'] = $amount;
        }

        Refund::create($refundArray);
    }

}


