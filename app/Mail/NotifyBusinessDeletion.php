<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyBusinessDeletion extends Mailable
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
        $this->subject($business->name . " is no longer with us");
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
        return $this->markdown('emails.account.notify-business-deletion')->with([
            'business'  => $this->business,
            'url'       => $url
        ]);
    }
}
