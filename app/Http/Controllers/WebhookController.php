<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Plan;
use App\StripeWebhook;
use App\Subscription;
use App\User;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Exception;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    private function getFailedPaymentEventList() {
        return [
            'invoice.payment_failed',
            'charge.failed'
        ];
    }

    private function getSuccessfulPaymentEventList() {
        return [
            'invoice.payment_succeeded',
        ];
    }

    public function verifyStripeEvent(Request $request, $webhooktype) {
        $payload = @file_get_contents('php://input');
        $endpointSecret = getWebHookKey($webhooktype);

        $sig_header = $_SERVER["HTTP_STRIPE_SIGNATURE"];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpointSecret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400); // PHP 5.4 or greater
            throw new \Exception($e->getMessage());
            exit();
        } catch(\Stripe\Error\SignatureVerification $e) {
            // Invalid signature
            http_response_code(400); // PHP 5.4 or greater
            throw new \Exception($e->getMessage());
            exit();
        }

        return $event;
    }


    public function failedPayment(Request $request) {
        setStripeApiKey('secret');
        $event = $this->verifyStripeEvent($request,StripeWebhook::PAYMENT_FAILED_WH_KEY);

        if (isset($event) && in_array($event->type, $this->getFailedPaymentEventList())) {
            $user           = User::where('stripe_id', $event->data->object->customer)->first();
            $subscription   = Subscription::where('stripe_id', $event->data->object->lines->data[0]->id)->first();

            if($subscription) {
                $plan = Plan::find($subscription->plan_id);
                $data['subscription']   = $subscription;
                $data['plan']           = $plan;
                $data['user']           = $user;
                $user->has_valid_payment_method = 0;
                $user->save();

                (new Notification())->sendFailedPaymentNotification($data);

            }
            return 1;
        }

        return 0;
    }

    public function successfulPayment(Request $request) {
        setStripeApiKey('secret');
        $event = $this->verifyStripeEvent($request,StripeWebhook::PAYMENT_SUCCEEDED_WH_KEY);

        if (isset($event) && in_array($event->type, $this->getSuccessfulPaymentEventList())) {

            try {

                $customerId = $event->data->object->customer;
                $user       = (new User())->where('stripe_id', $customerId)->first();
                $chargeId   = $event->data->object->object == "charge" ? $event->data->object->id : $event->data->object->charge;

                (new Subscription())->where('user_id', $user->id)
                    ->update(['last_charge_id' => $chargeId]);

            } catch (Exception $e) {

                logException($e);
                return 0;
            }

            return 1;
        }

        return 0;
    }

}
