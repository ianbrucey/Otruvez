<?php

namespace App;

use App\Mail\AcknowledgeContact;
use App\Mail\ConfirmAccount;
use App\Mail\ContactUs;
use App\Mail\FailedPayment;
use App\Mail\MessageToCustomers;
use App\Mail\NotifyBusinessDeletion;
use App\Mail\NotifyBusinessModification;
use App\Mail\NotifyPlanDeletion;
use App\Mail\NotifyPlanModification;
use App\Mail\SubscribedUser;
use App\Mail\UnsubscribedUser;
use App\Mail\WelcomeBusiness;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Email extends Model
{

    public static function sendConfirmAccountEmail(User $toUser, $activationToken) { // done
        return Mail::to($toUser->email)->queue(new ConfirmAccount($toUser->first , $toUser->email, $activationToken));
    }

    public static function sendNotifyBusinessDeletionEmail(User $toUser, Business $business) { //  done
        return Mail::to($toUser->email)->queue(new NotifyBusinessDeletion($business));
    }

    public static function sendNotifyBusinessModificationEmail(User $toUser, Business $business) { // done
        return Mail::to($toUser->email)->queue(new NotifyBusinessModification($business));
    }

    public static function sendNotifyPlanDeletionEmail(User $toUser, Business $business) { // done
        return Mail::to($toUser->email)->queue(new NotifyPlanDeletion($business));
    }

    public static function sendNotifyPlanModificationEmail(User $toUser, Business $business) { // done
        return Mail::to($toUser->email)->queue(new NotifyPlanModification($business));
    }

    public static function sendSubscribedUserEmail(User $toUser, $body) { // done
        return Mail::to($toUser->email)->queue(new SubscribedUser($body));
    }

    public static function sendUnsubscribedUserEmail(User $toUser, $body) { //done
        return Mail::to($toUser->email)->queue(new UnsubscribedUser($body));
    }

    public static function sendWelcomeBusinessEmail(User $toUser, Business $business, $body) { // done
        return Mail::to($toUser->email)->queue(new WelcomeBusiness($business, $body));
    }

    public static function sendMessageToCustomersEmail(User $toUser, Business $business, $body) { // done
        return Mail::to($toUser->email)->queue(new MessageToCustomers($business, $body));
    }

    public static function sendMessageToOtruvezSupport($subject, $text, $emailAddress) { // done
        return Mail::to("otruvez@gmail.com")->queue(new ContactUs($subject, $text, $emailAddress));
    }

    public static function acknowledgeContact($emailAddress) { // done
        return Mail::to($emailAddress)->queue(new AcknowledgeContact());
    }

    public static function sendFailedPaymentEmail(User $toUser, Plan $plan) { // done
        return Mail::to($toUser->email)->queue(new FailedPayment($toUser, $plan));
    }
}
