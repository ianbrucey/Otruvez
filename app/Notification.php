<?php

namespace App;

use App\Mail\ConfirmAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Plan;
use App\Business;

class Notification extends Model
{
    /** CONSUMER notification types */

    /**
     * email: false
     */
    const WELCOME_USER_NOTIFICATION             = [ // done
        'type'              => 'welcome_user',
        'subject'           => 'Welcome to Otruvez!',
        'body_template'     => 'notifications.templates.welcome-user' // body will be a template of some sort
    ];

    /**
     * email: true
     */
    const WELCOME_BUSINESS_NOTIFICATION     = [ // done
        'type'              => 'welcome_business',
        'subject'           => 'Congrats! now sell some subscriptions',
        'body_template'     => 'notifications.templates.welcome-business' // body will be a template of some sort
    ];

    const FAILED_PAYMENT_NOTIFICATION     = [ // done
        'type'              => 'failed_payment',
        'subject'           => 'Payment Failed',
        'body_template'     => 'notifications.webhook-templates.failed_payment' // body will be a template of some sort
    ];

    /**
     * email: true
     */
    const SUBSCRIBED_USER_NOTIFICATION           = [ //  done for USER but need flash notification for businesses
        'type'              => 'subscribed_user',
        'subject'           => 'You have a new subscription!', // concatenate Company name at the end
        'body_template'     => 'notifications.templates.subscribed-user' // body will be a template of some sort
    ];

    /**
     * email: true
     */
    const UNSUBSCRIBED_USER_NOTIFICATION         = [ // done
        'type'              => 'unsubscribed_user',
        'subject'           => 'Confirmation: Canceled subscription',
        'body_template'     => 'notifications.templates.unsubscribed-user' // body will be a template of some sort
    ];

    /**
     * email: true
     */
    const NOTIFY_PLAN_DELETION_NOTIFICATION         = [ // done
        'type'              => 'notify_plan_deletion',
        'subject'           => "Subscription canceled. Service no longer available",
        'body_template'     => 'notifications.templates.notify-plan-deletion' // body will be a template of some sort
    ];

    /**
     * email: true
     */
    const NOTIFY_PLAN_MODIFICATION_NOTIFICATION         = [ // done
        'type'              => 'notify_plan_modification',
        'subject'           => 'Details on a subscription you own have changed',
        'body_template'     => 'notifications.templates.notify-plan-modification' // body will be a template of some sort
    ];

    /**
     * email: true
     */
    const NOTIFY_BUSINESS_DELETION_NOTIFICATION         = [ // done
        'type'              => 'notify_business_deletion',
        'subject'           => 'Subscription canceled. Business no longer exists',
        'body_template'     => 'notifications.templates.notify-business-deletion' // body will be a template of some sort
    ];

    /**
     * email: true
     */
    const NOTIFY_BUSINESS_MODIFICATION_NOTIFICATION         = [ // done
        'type'              => 'notify_business_modification',
        'subject'           => "We've changed some details about our business",
        'body_template'     => 'notifications.templates.notify-business-modification' // body will be a template of some sort
    ];

    /**
     * email: true
     */
    const MESSAGE_TO_CUSTOMERS_NOTIFICATION         = [ // done
        'type'              => 'message_to_customers',
        'subject'           => 'A message to our customers',
        'body_template'     => 'notifications.templates.message-to-customers' // body will be a template of some sort
    ];

//    const PAYMENT_UNSUCCESSFUL_NOTIFICATION         = [
//        'type'              => 'payment_unsuccessful',
//        'subject'           => 'Welcome to Otruvez!',
//        'body_template'     => 'notifications.templates.welcome-user' // body will be a template of some sort
//    ];

//    const ALL_USERS_NOTIFICATION         = [
//        'type'              => 'welcome_user',
//        'subject'           => 'Welcome to Otruvez!',
//        'body_template'     => 'notifications.templates.welcome-user' // body will be a template of some sort
//    ];

    /** CONSUMER notification types END */

    const SUPPORT_ACKNOWLEDGE_NOTIFICATION  = [
        'type'              => 'support_acknowledge',
        'subject'           => 'Otruvez Support',
        'body_template'     => 'notifications.templates.support-acknowledge'
    ];
//
//    const SUPPORT_RESPONSE_NOTIFICATION     = [
//        'type_id'           => 3,
//        'type'              => 'support_acknowledge',
//        'subject'           => 'Otruvez Support',
//        'body_template'     => 'notifications.templates.support-acknowledge'
//    ];

    /** email types */


    public function getNotifications($id = null)
    {
        return $this->where('recipient_id', $id)->orderBy('id', 'desc')->get();
    }

    public function getBusinessNotifications($businessId)
    {
        return $this->where('business_id', $businessId)->where('recipient_id', Auth::id())->orderBy('id', 'desc')->get();
    }


    public function getNotfication($notificationId)
    {
        $notification = Notification::find($notificationId);
        return $notification;
    }

    /**
     * @param $notificationType
     * @param array $data
     * @return $this|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function renderNotificationView($notificationType, $data = [])
    {
        if($notificationType == self::WELCOME_USER_NOTIFICATION['type']) {
            return view($this->body_template)->with(['user' => Auth::user()]);
        }

        if($notificationType == self::WELCOME_BUSINESS_NOTIFICATION['type']) {
            return view($this->body_template);
        }

        if($notificationType == self::SUBSCRIBED_USER_NOTIFICATION['type']) {
            $subscription       = $data['subscription'];
            $plan               = Plan::find($subscription->plan_id);
            $business           = Business::find($subscription->business_id);

            return view($this->body_template)->with([
                'companyName'       => $business->name,
                'interval'          => $subscription->o_interval,
                'price'             => formatPrice($subscription->price),
                'usageDescription'  => getUseLimitString($plan),
                'serviceName'       => $plan->stripe_plan_name,
                'description'       => $plan->description,
                'logoPath'          => $business->logo_path ?: ''// html
            ]);
        }

        if($notificationType == self::UNSUBSCRIBED_USER_NOTIFICATION['type']) {
            $subscription       = Subscription::find($data['subscriptionId']);
            $plan               = Plan::find($subscription->plan_id);
            $business           = Business::find($subscription->business_id);

            return view($this->body_template)->with([
                'companyName'       => $business->name,
                'serviceName'       => $plan->stripe_plan_name,
                'confirmationId'    => $subscription->stripe_id,
                'logoPath'          => $business->logo_path ?: ''// html
            ]);
        }

        if($notificationType == self::NOTIFY_PLAN_DELETION_NOTIFICATION['type']) {
            $subscription       = $data['subscription'];
            $plan               = $data['plan'];
            $business           = $data['business'];
            $refundStatus       = $data['refundStatus']; // array

            return view($this->body_template)->with([
                'companyName'       => $business->name,
                'serviceName'       => $plan->stripe_plan_name,
                'confirmationId'    => $subscription->stripe_id,
                'refundStatus'      => $refundStatus,
                'logoPath'          => $business->logo_path ?: ''// html
            ]);
        }

        if($notificationType == self::NOTIFY_PLAN_MODIFICATION_NOTIFICATION['type']) {
            $subscription       = $data['subscription'];
            $plan               = Plan::find($subscription->plan_id);
            $business           = Business::find($subscription->business_id);

            return view($this->body_template)->with([
                'oldName'           => $data['oldName'],
                'oldDescription'    => $data['oldDescription'],
                'newName'           => $plan->stripe_plan_name,
                'newDescription'    => $plan->description,
                'companyName'       => $business->name,
                'logoPath'          => $business->logo_path ?: ''// html
            ]);
        }

        if($notificationType == self::NOTIFY_BUSINESS_DELETION_NOTIFICATION['type']) {
            return view($this->body_template)->with([
                'business'      => $data['business'],
                'plan'          => $data['plan'],
                'refundStatus'  => $data['refundStatus']
            ]);
        }

        if($notificationType == self::NOTIFY_BUSINESS_MODIFICATION_NOTIFICATION['type']) {
            return view($this->body_template)->with([
                'business'  => $data['business'],
                'days'      => $data['days']
            ]);
        }

        if($notificationType == self::FAILED_PAYMENT_NOTIFICATION['type']) {
            return view($this->body_template)->with([
                'user'          => $data['user'],
                'plan'          => $data['plan']
            ]);
        }

        if($notificationType == self::SUPPORT_ACKNOWLEDGE_NOTIFICATION['type']) {
            return view($this->body_template)->with('user', Auth::user());
        }

        return view();

    }

    public function sendWelcomeNotification($user)
    {
        $this->type                 = self::WELCOME_USER_NOTIFICATION['type'];
        $this->subject              = self::WELCOME_USER_NOTIFICATION['subject'];
        $this->body_template        = self::WELCOME_USER_NOTIFICATION['body_template']; // template?
        $this->sender_name          = env('APP_NAME');
        $this->is_template          = true;
        $this->recipient_id         = $user->id;
        return $this->save();
    }

    public function sendSubscribedUserNotification($user, $business, $subscription)
    {

        $this->type                 = self::SUBSCRIBED_USER_NOTIFICATION['type'];
        $this->body_template        = self::SUBSCRIBED_USER_NOTIFICATION['body_template']; // template?
        $this->subject              = self::SUBSCRIBED_USER_NOTIFICATION['subject'];
        $this->sender_name          = $business->name;
        $this->is_template          = "0";
        $this->body                 = $this->renderNotificationView(self::SUBSCRIBED_USER_NOTIFICATION['type'],['subscription' => $subscription])->render();;
        $this->recipient_id         = $user->id;
        $this->business_id          = $business->id;
        $this->subscription_id      = $subscription->id;
        $this->save();

        return Email::sendSubscribedUserEmail($user, $this->body);
    }

    public function sendUnsubscribedUserNotification($user, $business, $subscription)
    {
        $this->type                 = self::UNSUBSCRIBED_USER_NOTIFICATION['type'];
        $this->body_template        = self::UNSUBSCRIBED_USER_NOTIFICATION['body_template'];
        $this->body                 = $this->renderNotificationView(self::UNSUBSCRIBED_USER_NOTIFICATION['type'],['subscriptionId' => $subscription->id])->render(); // template?
        $this->subject              = self::UNSUBSCRIBED_USER_NOTIFICATION['subject'];
        $this->sender_name          = $business->name;
        $this->is_template          = "0";
        $this->recipient_id         = $user->id;
        $this->subscription_id      = $subscription->id;
        $this->save();

        // business should get one too

        return Email::sendUnsubscribedUserEmail($user, $this->body);
    }

    public function sendNotifyPlanDeletionNotification($business, $subscription, $data)
    {
        $this->type                 = self::NOTIFY_PLAN_DELETION_NOTIFICATION['type'];
        $this->body_template        = self::NOTIFY_PLAN_DELETION_NOTIFICATION['body_template'];
        $this->body                 = $this->renderNotificationView(self::NOTIFY_PLAN_DELETION_NOTIFICATION['type'],$data)->render(); // template?
        $this->subject              = self::NOTIFY_PLAN_DELETION_NOTIFICATION['subject'];
        $this->sender_name          = $business->name;
        $this->is_template          = "0";
        $this->recipient_id         = $subscription->user_id;
        $this->subscription_id      = $subscription->id;
        $this->save();

        $user = User::find($subscription->user_id);
        return Email::sendNotifyPlanDeletionEmail($user, $business);
    }

    public function sendNotifyBusinessDeletionNotification($business, $subscription, $data)
    {
        $data['business']           = $business;
        $data['plan']               = Plan::find($subscription->plan_id);
        $this->type                 = self::NOTIFY_BUSINESS_DELETION_NOTIFICATION['type'];
        $this->body_template        = self::NOTIFY_BUSINESS_DELETION_NOTIFICATION['body_template'];
        $this->body                 = $this->renderNotificationView(self::NOTIFY_BUSINESS_DELETION_NOTIFICATION['type'],$data)->render(); // template?
        $this->subject              = self::NOTIFY_BUSINESS_DELETION_NOTIFICATION['subject'];
        $this->sender_name          = env('APP_NAME');
        $this->is_template          = "0";
        $this->recipient_id         = $subscription->user_id;
        $this->subscription_id      = $subscription->id;
        $this->save();

        $user = User::find($subscription->user_id);
        return Email::sendNotifyBusinessDeletionEmail($user, $business);
    }

    public function sendNotifyPlanModificationNotification($business, $subscription, $data)
    {
        $this->type                 = self::NOTIFY_PLAN_MODIFICATION_NOTIFICATION['type'];
        $this->body_template        = self::NOTIFY_PLAN_MODIFICATION_NOTIFICATION['body_template'];
        $this->body                 = $this->renderNotificationView(self::NOTIFY_PLAN_MODIFICATION_NOTIFICATION['type'],$data)->render(); // template?
        $this->subject              = self::NOTIFY_PLAN_MODIFICATION_NOTIFICATION['subject'];
        $this->sender_name          = $business->name;
        $this->is_template          = "0";
        $this->recipient_id         = $subscription->user_id;
        $this->subscription_id      = $subscription->id;
        $this->save();

        $user = User::find($subscription->user_id);
        return Email::sendNotifyPlanModificationEmail($user, $business);
    }

    public function sendNotifyBusinessModificationNotification($business, $subscription)
    {
        $data                       = [];
        $data['days']               = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        $data['business']           = $business;
        $this->type                 = self::NOTIFY_BUSINESS_MODIFICATION_NOTIFICATION['type'];
        $this->body_template        = self::NOTIFY_BUSINESS_MODIFICATION_NOTIFICATION['body_template'];
        $this->body                 = $this->renderNotificationView(self::NOTIFY_BUSINESS_MODIFICATION_NOTIFICATION['type'],$data)->render(); // template?
        $this->subject              = self::NOTIFY_BUSINESS_MODIFICATION_NOTIFICATION['subject'];
        $this->sender_name          = $business->name;
        $this->is_template          = "0";
        $this->recipient_id         = $subscription->user_id;
        $this->subscription_id      = $subscription->id;
        $this->save();

        $user = User::find($subscription->user_id);
        return Email::sendNotifyBusinessModificationEmail($user, $business);
    }

    public function sendBusinessWelcomeNotification($user, $business)
    {
        $this->type                 = self::WELCOME_BUSINESS_NOTIFICATION['type'];
        $this->subject              = self::WELCOME_BUSINESS_NOTIFICATION['subject'];
        $this->body_template        = self::WELCOME_BUSINESS_NOTIFICATION['body_template']; // template?
        $this->body                 = $this->renderNotificationView(self::WELCOME_BUSINESS_NOTIFICATION['type'])->render();
        $this->sender_name          = env('APP_NAME') ?: "Otruvez";
        $this->is_template          = true;
        $this->recipient_id         = $user->id;
        $this->business_id          = $business->id;
        $this->save();

        return Email::sendWelcomeBusinessEmail($user,$business,$this->body);
    }

    public function sendMessageToCustomersNotification($business, $subscription, $data)
    {
        $this->type                 = self::MESSAGE_TO_CUSTOMERS_NOTIFICATION['type'];
        $this->subject              = $data['subject'];
        $this->body                 = $data['body'];
        $this->body_template        = self::MESSAGE_TO_CUSTOMERS_NOTIFICATION['body_template']; // template?
        $this->sender_name          = $business->name;
        $this->is_template          = "0";
        $this->recipient_id         = $subscription->user_id;
        $this->subscription_id      = $subscription->id;
        $this->business_id          = $business->id;
        $this->save();

        $user = User::find($subscription->user_id);

        return Email::sendMessageToCustomersEmail($user,$business,$this->body);
    }

    public function sendSupportNotification(Request $request)
    {
        $this->type         = $request->get('type');
        $this->recipient_id = "0";
        $this->subject      = $request->get('subject');
        $this->body         = $request->get('body');
        $this->save(); // sends to us

        $userNotification                   = new Notification(); // sends to the user
        $userNotification->type             = self::SUPPORT_ACKNOWLEDGE_NOTIFICATION['type'];
        $userNotification->subject          = self::SUPPORT_ACKNOWLEDGE_NOTIFICATION['subject'];
        $userNotification->body_template    = self::SUPPORT_ACKNOWLEDGE_NOTIFICATION['body_template'];
        $userNotification->body             = $userNotification->renderNotificationView(self::SUPPORT_ACKNOWLEDGE_NOTIFICATION['type'])->render();
        $userNotification->recipient_id     = Auth::id();

        return $userNotification->save();
    }

    public function sendFailedPaymentNotification($data)
    {
        $subscription               = $data['subscription'];
        $this->type                 = self::FAILED_PAYMENT_NOTIFICATION['type'];
        $this->subject              = self::FAILED_PAYMENT_NOTIFICATION['subject'];
        $this->body_template        = self::FAILED_PAYMENT_NOTIFICATION['body_template']; // template?
        $this->body                 = $this->renderNotificationView(self::FAILED_PAYMENT_NOTIFICATION['type'],$data)->render(); // needs user and plan
        $this->sender_name          = env('APP_NAME');
        $this->is_template          = "0";
        $this->recipient_id         = $subscription->user_id;
        $this->subscription_id      = $subscription->id;
        $this->save();

        $user = User::find($subscription->user_id);

        //Since Stripe will send an email for us, we don't need to send an email. just the notification
        //Email::sendFailedPaymentEmail($user,$data['plan']);

        return true;
    }

    public function completeSubject($str1, $str2) {
        return sprintf('%s%s',$str1, $str2);
    }

}
