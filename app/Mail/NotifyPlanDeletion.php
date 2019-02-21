<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyPlanDeletion extends Mailable
{
    use Queueable, SerializesModels;

    private $business;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($business)
    {
        $this->business = $business;
        $this->subject("We've canceled a subscription for you");
        $this->from("otruvez@gmail.com", 'Otruvez');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = getAccountNotificationsUrl();
        return $this->markdown('emails.account.notify-plan-deletion')->with([
            'business'  => $this->business,
            'url'       => $url
        ]);
    }
}
