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

    public static function getRefundStatusAndAmount($subscription, $refundAmountOnly = false) {

        $plan = (new Plan())->find($subscription->plan_id);
        $customer = (new User())->find($subscription->user_id);
        $limitExceeded = isUsageLimitExceeded($subscription, $plan);
        $usageData =  calculateRemainingUses($plan, $subscription);
        $refundStatus = [
            'refund' => false,
            'amount' => 0,
            'pennies'=> 0
        ];

        if($customer->has_valid_payment_method && !$limitExceeded && $usageData['limitInterval'] && $usageData['usesRemaining'] != 0) { // if user is at usage limit, no refund, else prorate
            $refundStatus['refund'] = true;
            $refundAmount = ($usageData['usesRemaining'] / $usageData['useLimit']) * $subscription->price ;
            $refundStatus['amount'] = formatPrice($refundAmount);
            $refundStatus['pennies'] = $refundAmount;

            if (!$refundAmountOnly) {
                self::issueRefund($subscription, $refundAmount); // here we will issue the refund
            }
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


