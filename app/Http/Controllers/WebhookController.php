<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Plan;
use App\Subscription;
use App\User;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    private function getFailedPaymentEventList() {
        return [
            'invoice.payment_failed',
            'charge.failed'
        ];
    }

    public function verifyStripeEvent(Request $payload) {
        $endpointSecret = getWebHookKey();

        $sig_header = $_SERVER["HTTP_STRIPE_SIGNATURE"];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpointSecret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400); // PHP 5.4 or greater
            exit();
        } catch(\Stripe\Error\SignatureVerification $e) {
            // Invalid signature
            http_response_code(400); // PHP 5.4 or greater
            exit();
        }

        return $event;
    }


    public function failedPayment(Request $request) {
        setStripeApiKey('secret');
        $event = $this->verifyStripeEvent($request);

        if (isset($event) && in_array($event->type, $this->getFailedPaymentEventList())) {
            $user           = User::where('stripe_id', $event->data->object->customer)->first();
            $subscription   = Subscription::where('stripe_id', $event->data->object->lines->data[0]->id)->first();

            if($subscription) {
                $plan = Plan::find($subscription->plan_id);
                $subscription->status = "0";
                $subscription->save();
                $data['subscription']   = $subscription;
                $data['plan']           = $plan;
                $data['user']           = $user;

                (new Notification())->sendFailedPaymentNotification($data);

            }
            // need to retrieve the subscription as well
        }

        return 1;
    }

}
